<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\Authentication\AuthenticationService;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Middleware\LogoutMiddleware;
use SimpleUserManager\Middleware\LogoutMiddlewareFactory;
use SimpleUserManagerTest\InMemoryContainer;

class LogoutMiddlewareFactoryTest extends TestCase
{
    public function testFactoryWithTemplate(): void
    {
        /** @var AuthenticationService&MockObject $authService */
        $authService = $this->createMock(AuthenticationService::class);

        $container = new InMemoryContainer();
        $container->setService(AuthenticationService::class, $authService);

        $factory = new LogoutMiddlewareFactory();
        $handler = $factory($container);

        self::assertInstanceOf(LogoutMiddleware::class, $handler);
    }
}
