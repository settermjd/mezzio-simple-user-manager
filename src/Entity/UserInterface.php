<?php

declare(strict_types=1);

namespace Settermjd\SimpleUserManager\Entity;

interface UserInterface
{
    public function toArray(): array;

    public function getId(): int|null;

    public function getEmailAddress(): string|null;

    public function getFirstName(): string|null;
}
