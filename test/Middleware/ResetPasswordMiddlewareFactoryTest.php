<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\EventManager\EventManagerInterface;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Middleware\ResetPasswordMiddleware;
use SimpleUserManager\Middleware\ResetPasswordMiddlewareFactory;
use SimpleUserManager\Service\ResetPassword\Adapter\AdapterInterface;
use SimpleUserManager\Validator\ResetPasswordValidator;
use SimpleUserManagerTest\InMemoryContainer;

class ResetPasswordMiddlewareFactoryTest extends TestCase
{
    public function testFactoryWithTemplate(): void
    {
        /** @var EventManagerInterface&MockObject $eventManager */
        $eventManager = $this->createMock(EventManagerInterface::class);

        /** @var AdapterInterface&MockObject $adapter */
        $adapter = $this->createMock(AdapterInterface::class);

        $container = new InMemoryContainer();
        $container->setService(AdapterInterface::class, $adapter);
        $container->setService(ResetPasswordValidator::class, new ResetPasswordValidator());
        $container->setService(EventManagerInterface::class, $eventManager);

        $factory = new ResetPasswordMiddlewareFactory();
        $handler = $factory($container);

        self::assertInstanceOf(ResetPasswordMiddleware::class, $handler);
    }
}
