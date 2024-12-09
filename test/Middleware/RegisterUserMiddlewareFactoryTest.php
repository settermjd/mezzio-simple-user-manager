<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Hydrator\NamingStrategy\NamingStrategyInterface;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Middleware\RegisterUserMiddleware;
use SimpleUserManager\Middleware\RegisterUserMiddlewareFactory;
use SimpleUserManager\Service\RegisterUser\Adapter\AdapterInterface;
use SimpleUserManager\Validator\RegisterUserValidator;
use SimpleUserManagerTest\InMemoryContainer;

class RegisterUserMiddlewareFactoryTest extends TestCase
{
    public function testFactoryWithTemplate(): void
    {
        $container = new InMemoryContainer();
        $container->setService(
            AdapterInterface::class,
            /** @param AdapterInterface&MockObject */
            $this->createMock(AdapterInterface::class)
        );
        $container->setService(
            RegisterUserValidator::class,
            /** @param RegisterUserValidator&MockObject */
            new RegisterUserValidator()
        );
        $container->setService(
            EventManagerInterface::class,
            /** @param EventManagerInterface&MockObject */
            $this->createMock(EventManagerInterface::class)
        );
        $container->setService(
            NamingStrategyInterface::class,
            /** @param NamingStrategyInterface&MockObject */
            $this->createMock(NamingStrategyInterface::class)
        );

        $factory = new RegisterUserMiddlewareFactory();
        $handler = $factory($container);

        self::assertInstanceOf(RegisterUserMiddleware::class, $handler);
    }
}
