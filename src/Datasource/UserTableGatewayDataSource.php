<?php

declare(strict_types=1);

namespace Settermjd\SimpleUserManager\Datasource;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\TableGateway\Feature;
use Laminas\Db\TableGateway\TableGateway;
use Settermjd\SimpleUserManager\Entity\UserInterface;

use function array_filter;

class UserTableGatewayDataSource extends TableGateway implements UserDatasourceInterface
{
    public function __construct(AdapterInterface $adapter, ResultSetInterface $resultSet)
    {
        parent::__construct(
            'user',
            $adapter,
            [
                new Feature\MetadataFeature(),
            ],
            $resultSet,
        );
    }

    public function getUser(UserInterface $user): UserInterface
    {
        /** @var HydratingResultSet $result */
        $result = $this->select($user->toArray());

        /** @var UserInterface $user */
        $user = $result->current();

        return $user;
    }

    public function removeUser(UserInterface $user)
    {
        $this->delete($user->toArray());
    }

    public function updateUser(UserInterface $user): UserInterface
    {
        $this->update($user->toArray(), $user->getId());

        return $this->findByEmailAddress($user->getEmailAddress());
    }

    public function addUser(UserInterface $user): UserInterface
    {
        $this->insert($user->toArray());

        return $this->findByEmailAddress($user->getEmailAddress());
    }

    public function findByEmailAddress(string $emailAddress): UserInterface
    {
        return $this->select(["email" => $emailAddress])->current();
    }

    public function userExists(UserInterface $user): bool
    {
        return $this->select(array_filter($user->toArray()))->count() === 1;
    }
}
