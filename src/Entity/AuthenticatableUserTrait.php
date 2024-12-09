<?php

declare(strict_types=1);

namespace Settermjd\SimpleUserManager\Entity;

trait AuthenticatableUserTrait
{
    private string $username;
    private string $password;
}
