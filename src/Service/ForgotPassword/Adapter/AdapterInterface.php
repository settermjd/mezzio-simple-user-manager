<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ForgotPassword\Adapter;

interface AdapterInterface
{
    public function forgotPassword(string $userIdentity): bool;
}
