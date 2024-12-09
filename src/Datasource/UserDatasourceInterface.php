<?php

declare(strict_types=1);

namespace Settermjd\SimpleUserManager\Datasource;

use Settermjd\SimpleUserManager\Entity\User;
use Settermjd\SimpleUserManager\Entity\UserInterface;

interface UserDatasourceInterface
{
    /**
     * Retrieve a user from the underlying data source based on the details supplied
     */
    public function getUser(UserInterface $user): UserInterface;

    /**
     * Remove a user from the underlying data source based on the details supplied
     */
    public function removeUser(UserInterface $user);

    /**
     * Update details of a user in the underlying data source with the details supplied
     */
    public function updateUser(UserInterface $user): UserInterface;

    /**
     * Add a user to the underlying data source using the details supplied
     */
    public function addUser(UserInterface $user): UserInterface;

    /**
     * Check if a user exists in the underlying data source using the details supplied
     */
    public function userExists(User $user): bool;
}
