<?php

declare(strict_types=1);

namespace SimpleUserManager\Entity;

use function get_object_vars;
use function sprintf;
use function trim;

/**
 * This entity provides a simple model for a system user.
 */
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

    public function getLastName(): string|null
    {
        return $this->lastName;
    }

    public function getFullname(): string
    {
        return trim(sprintf("%s %s", $this->firstName, $this->lastName));
    }

    public function getPhone(): string|null
    {
        return $this->phone;
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
