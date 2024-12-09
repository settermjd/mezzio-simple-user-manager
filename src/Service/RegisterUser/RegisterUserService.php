<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\RegisterUser;

use SimpleUserManager\Service\RegisterUser\Adapter\AdapterInterface;

final class RegisterUserService implements RegisterUserServiceInterface
{
    public function __construct(private readonly AdapterInterface $adapter)
    {
    }

    public function registerUser(): Result
    {
    }
}
