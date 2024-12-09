<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\RegisterUser;

/**
 * Provides an API for resetting a user's password
 */
interface RegisterUserServiceInterface
{
    /**
     * Registers a new user with an underlying data source
     */
    public function registerUser(): Result;
}
