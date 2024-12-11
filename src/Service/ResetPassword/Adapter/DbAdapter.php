<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ResetPassword\Adapter;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface as DbAdapterInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Predicate\Expression;
use Laminas\Db\Sql\Sql;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Hydrator\ReflectionHydrator;
use SimpleUserManager\Entity\ResetPassword\ResetActive;
use SimpleUserManager\Exception\PasswordResetNotActiveForUserException;
use SimpleUserManager\Service\ResetPassword\Result;

/**
 * This class uses laminas-db to insert a record in a table in the database
 * identifying that a user wants to reset their password
 */
final readonly class DbAdapter implements AdapterInterface
{
    /**
     * @param string $identityColumn  This identifies the column in the underlying table
     *                                that identifies the user resetting their password
     * @param string $tableName       This identifies the table to insert a record into to
     *                                identify that a user is resetting their password
     */
    public function __construct(
        private DbAdapterInterface&Adapter $adapter,
        private string $tableName = "user",
        private string $passwordColumn = "password",
        private string $identityColumn = "email"
    ) {}

    /**
     * This function registers a hash in the identity column of the table in the
     * underlying database
     *
     * @todo Handle exceptions
     * @todo Handle missing identity value
     */
    public function resetPassword(string $identity, string $password): Result
    {
        // Check if the user exists before attempting to update them and handle
        // the case when they don't
        $sql    = new Sql($this->adapter);
        $select = $sql->select();
        $select
            ->from(["u" => $this->tableName])
            ->columns(
                [
                    "reset_active" => new Expression('IIF (COUNT(*) = 1, "yes", "no")'),
                ]
            )
            ->join(
                ['pr' => 'password_resets'],
                "u.email = pr.user_identity",
                [],
            )
            ->where([
                'pr.user_identity' => $identity,
                new Expression("(date('now', 'localtime') <= date(pr.created_at, '+5 days'))"),
            ]);

        $results = $this->adapter->query(
            $sql->buildSqlString($select),
            Adapter::QUERY_MODE_EXECUTE,
            new HydratingResultSet(new ReflectionHydrator(), new ResetActive())
        );

        /** @var ResetActive $result */
        $result = $results->current();
        if (! $result->isResetActive()) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                [
                    "The user does not have a password reset in effect"
                ]
            );
        }

        // Update the user's password
        $resetPasswordTable = new TableGateway($this->tableName, $this->adapter);
        $resetPasswordTable
            ->update(
                [
                    $this->passwordColumn => $password,
                ],
                [
                    $this->identityColumn => $identity,
                ]
            );

        return new Result(code: Result::SUCCESS);
    }
}
