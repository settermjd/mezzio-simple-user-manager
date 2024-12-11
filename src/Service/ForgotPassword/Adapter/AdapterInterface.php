<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ForgotPassword\Adapter;

use SimpleUserManager\Service\ForgotPassword\Result;

interface AdapterInterface
{
    public function forgotPassword(string $userIdentity): Result;
}
