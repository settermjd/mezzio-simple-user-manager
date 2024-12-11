<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\ResetPassword\Adapter;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Exception\PasswordResetNotActiveForUserException;
use SimpleUserManager\Service\ResetPassword\Adapter\DbAdapter;
use SimpleUserManagerTest\Service\DbTestTrait;

class DbAdapterTest extends TestCase
{
    use DbTestTrait;

    #[TestWith(["user@example.com", "password", false])]
    #[TestWith(["matthew@example.org", "password", false])]
    #[TestWith(["matthew@example.com", "password", true])]
    public function testCanSuccessfullyForgetPassword(
        string $userIdentity,
        string $password,
        bool $status
    ): void {
        if (! $status) {
            $this->expectException(PasswordResetNotActiveForUserException::class);
        }

        $middlewareAdapter = new DbAdapter(adapter: $this->getDbAdapter());

        $this->assertSame($status, $middlewareAdapter->resetPassword($userIdentity, $password));
    }
}
