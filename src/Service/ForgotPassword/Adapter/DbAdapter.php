<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ForgotPassword\Adapter;

use Laminas\Db\Adapter\AdapterInterface as DbAdapterInterface;
use Laminas\Db\TableGateway\TableGateway;

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
    ) {}

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
     * @todo Handle missing reset password hash
     */
    public function forgotPassword(string $userIdentity): bool
    {
        $userTable    = new TableGateway($this->tableName, $this->adapter);
        $affectedRows = $userTable->insert([
            $this->identityColumn => $userIdentity,
        ]);

        return $affectedRows === 1;
    }
}
