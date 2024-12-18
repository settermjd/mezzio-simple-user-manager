<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Hydrator\NamingStrategy\NamingStrategyInterface;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Handler\RegisterUserProcessorHandler;
use SimpleUserManager\Handler\RegisterUserProcessorHandlerFactory;
use SimpleUserManager\Service\RegisterUser\Adapter\AdapterInterface;
use SimpleUserManager\Validator\RegisterUserValidator;
use SimpleUserManagerTest\InMemoryContainer;

class RegisterUserProcessorHandlerFactoryTest extends TestCase
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

        $factory = new RegisterUserProcessorHandlerFactory();
        $handler = $factory($container);

        self::assertInstanceOf(RegisterUserProcessorHandler::class, $handler);
    }
}
