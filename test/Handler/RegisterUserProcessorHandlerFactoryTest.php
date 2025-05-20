<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use SimpleUserManager\Handler\RegisterUserProcessorHandler;
use SimpleUserManager\Handler\RegisterUserProcessorHandlerFactory;
use SimpleUserManager\Service\RegisterUser\Adapter\AdapterInterface;
use SimpleUserManager\Validator\RegisterUserValidator;
use SimpleUserManagerTest\InMemoryContainer;

class RegisterUserProcessorHandlerFactoryTest extends TestCase
{
    #[TestWith([true])]
    #[TestWith([false])]
    public function testFactoryWithTemplate(bool $hasLogger): void
    {
        $container = new InMemoryContainer();
        $container->setService(
            AdapterInterface::class,
            $this->createMock(AdapterInterface::class)
        );
        $container->setService(
            RegisterUserValidator::class,
            new RegisterUserValidator()
        );
        if ($hasLogger) {
            $container->setService(LoggerInterface::class, $logger = $this->createMock(LoggerInterface::class));
        }

        $factory = new RegisterUserProcessorHandlerFactory();
        $handler = $factory($container);

        self::assertInstanceOf(RegisterUserProcessorHandler::class, $handler);
    }
}
