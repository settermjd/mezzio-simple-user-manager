<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\ResetPassword\Adapter;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Service\ResetPassword\Adapter\DbAdapter;
use SimpleUserManager\Service\ResetPassword\Result;
use SimpleUserManagerTest\Service\DbTestTrait;

class DbAdapterTest extends TestCase
{
    use DbTestTrait;

    #[TestWith(["user@example.com", "password", Result::FAILURE_IDENTITY_NOT_FOUND])]
    #[TestWith(["matthew@example.org", "password", Result::FAILURE_IDENTITY_NOT_FOUND])]
    #[TestWith(["matthew@example.com", "password", Result::SUCCESS])]
    public function testCanSuccessfullyResetPassword(
        string $userIdentity,
        string $password,
        int $code
    ): void {
        $middlewareAdapter = new DbAdapter(adapter: $this->getDbAdapter());
        $result = $middlewareAdapter->resetPassword($userIdentity, $password);

        $this->assertInstanceOf(Result::class, $result);
        $this->assertSame($code, $result->getCode());
    }
}
