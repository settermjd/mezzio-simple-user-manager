<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\ForgotPassword\Adapter;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Service\ForgotPassword\Adapter\DbAdapter;
use SimpleUserManager\Service\ForgotPassword\Result;
use SimpleUserManagerTest\Service\DbTestTrait;

class DbAdapterTest extends TestCase
{
    use DbTestTrait;

    #[TestWith(["user@example.com", Result::SUCCESS])]
    #[TestWith(["matthew@example.org", Result::FAILURE_RECORD_EXISTS_FOR_PROVIDED_IDENTITY])]
    public function testCanSuccessfullyForgetPassword(string $userIdentity, int $code): void
    {
        $this->setUpDatabase();

        $middlewareAdapter = new DbAdapter(adapter: $this->getDbAdapter());

        /** @var Result $result */
        $result = $middlewareAdapter->forgotPassword($userIdentity);
        $this->assertInstanceOf(Result::class, $result);
        $this->assertSame($code, $result->getCode());

        //$this->tearDownDatabase();
    }
}
