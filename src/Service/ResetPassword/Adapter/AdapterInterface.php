<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ResetPassword\Adapter;

interface AdapterInterface
{
    /**
     * resetPassword resets a user's password in the underlying data source
     * using $identity as the value to identify them with, and password as the
     * user's new password. How the password should be treated, such as MD5,
     * SHA512, etc, is handled by the AdapterInterface implementation, not at
     * this level of abstraction.
     */
    public function resetPassword(string $identity, string $password): bool;
}
