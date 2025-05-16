<?php

declare(strict_types=1);

namespace SimpleUserManager\Entity;

interface UserInterface
{
    /**
     * @return array<mixed>
     */
    public function toArray(): array;

    public function getId(): int|null;

    public function getEmailAddress(): string|null;

    public function getFirstName(): string|null;
}
