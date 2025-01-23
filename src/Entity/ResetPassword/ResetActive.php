<?php

declare(strict_types=1);

namespace SimpleUserManager\Entity\ResetPassword;

class ResetActive
{
    private string $resetActive;

    public function isResetActive(): bool
    {
        return $this->resetActive === "yes";
    }
}
