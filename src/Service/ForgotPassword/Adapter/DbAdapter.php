<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ForgotPassword\Adapter;

use Laminas\Db\Adapter\AdapterInterface as DbAdapterInterface;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\TableGateway\TableGateway;
use SimpleUserManager\Service\ForgotPassword\Result;

/**
 * This class uses laminas-db to insert a record in a table in the database
 * identifying that a user wants to reset their password
 */
final readonly class DbAdapter implements AdapterInterface
{
    /**
     * @param string $identityColumn This identifies the column in the underlying table
     *                               that identifies the user resetting their password
     * @param string $tableName      This identifies the table to insert a record into to
     *                               identify that a user is resetting their password
     */
    public function __construct(
        private DbAdapterInterface $adapter,
        private string $tableName = "password_resets",
        private string $identityColumn = "user_identity",
    ) {
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getIdentityColumn(): string
    {
        return $this->identityColumn;
    }

    /**
     * This function updates a user's password and removes the hash from the
     * reset password table
     *
     * @todo Handle exceptions
     * @todo Handle existing record
     * @todo Handle existing reset password entry
     */
    public function forgotPassword(string $userIdentity): Result
    {
        $userTable = new TableGateway($this->tableName, $this->adapter);
        try {
            $userTable->insert([
                $this->identityColumn => $userIdentity,
            ]);
        } catch (InvalidQueryException $e) {
            return new Result(
                code: Result::FAILURE_RECORD_EXISTS_FOR_PROVIDED_IDENTITY,
                messages: [
                    $e->getMessage(),
                ]
            );
        }

        return new Result(Result::SUCCESS);
    }
}
