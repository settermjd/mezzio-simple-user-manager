<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\RegisterUser\Adapter;

use Laminas\Db\Adapter\AdapterInterface as DbAdapterInterface;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\Db\TableGateway\TableGateway;
use Mezzio\Authentication\UserInterface;
use SimpleUserManager\Service\RegisterUser\Result;

final readonly class DbAdapter implements AdapterInterface
{
    /**
     * @param string $tableName  This identifies the table to insert a record into to
     *                           identify that a user is resetting their password
     */
    public function __construct(
        private DbAdapterInterface $adapter,
        private string $tableName = "user"
    ) {}

    /**
     * This function registers a new user in the underlying database
     *
     * @todo Handle an existing user with the same details (or the essential ones)
     */
    public function registerUser(UserInterface $user): Result
    {
        $resetPasswordTable = new TableGateway($this->tableName, $this->adapter);
        try {
            $resetPasswordTable->insert($user->getDetails());
        } catch (InvalidQueryException $e) {
            return new Result(Result::FAILURE);
        }

        return new Result(Result::SUCCESS);
    }
}
