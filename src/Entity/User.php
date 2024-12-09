<?php

declare(strict_types=1);

namespace Settermjd\SimpleUserManager\Entity;

use function get_object_vars;

final class User implements UserInterface
{
    use AuthenticatableUserTrait;

    public function __construct(
        private int|null $id = null,
        private string|null $firstName = null,
        private string|null $lastName = null,
        private string|null $email = null,
        private string|null $phone = null,
    ) {
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getEmailAddress(): string|null
    {
        return $this->email;
    }

    public function getFirstName(): string|null
    {
        return $this->firstName;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
