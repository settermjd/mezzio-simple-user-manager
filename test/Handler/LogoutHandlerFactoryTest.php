<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\Authentication\AuthenticationService;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Handler\LogoutHandler;
use SimpleUserManager\Handler\LogoutHandlerFactory;
use SimpleUserManagerTest\InMemoryContainer;

class LogoutHandlerFactoryTest extends TestCase
{
    public function testFactoryWithTemplate(): void
    {
        $authService = $this->createMock(AuthenticationService::class);

        $container = new InMemoryContainer();
        $container->setService(AuthenticationService::class, $authService);

        $factory = new LogoutHandlerFactory();
        $handler = $factory($container);

        self::assertInstanceOf(LogoutHandler::class, $handler);
    }
}
