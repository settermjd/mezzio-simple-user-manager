<?php

declare(strict_types=1);

namespace SimpleUserManager\Entity;

trait AuthenticatableUserTrait
{
    private string $username;
    private string $password;
}
