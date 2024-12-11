<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\ForgotPassword\Adapter;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Service\ForgotPassword\Adapter\DbAdapter;
use SimpleUserManagerTest\Service\DbTestTrait;

class DbAdapterTest extends TestCase
{
    use DbTestTrait;

    #[TestWith(["user@example.com"])]
    #[TestWith(["matthew@example.org"])]
    public function testCanSuccessfullyForgetPassword(string $userIdentity): void
    {
        $this->setUpDatabase();

        $middlewareAdapter = new DbAdapter(adapter: $this->getDbAdapter());

        $this->assertTrue($middlewareAdapter->forgotPassword($userIdentity));

        $this->tearDownDatabase();
    }
}
