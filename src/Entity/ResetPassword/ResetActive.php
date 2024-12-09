<?php

declare(strict_types=1);

namespace SimpleUserManager\Entity\ResetPassword;

class ResetActive
{
    private string $reset_active;

    public function isResetActive(): bool
    {
        return $this->reset_active === "yes";
    }
}
