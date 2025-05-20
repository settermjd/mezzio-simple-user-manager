<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\EventManager\EventManagerInterface;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use SimpleUserManager\Handler\ForgotPasswordProcessorHandler;
use SimpleUserManager\Handler\ForgotPasswordProcessorHandlerFactory;
use SimpleUserManager\Service\ForgotPassword\Adapter\AdapterInterface;
use SimpleUserManager\Validator\ForgotPasswordValidator;
use SimpleUserManagerTest\InMemoryContainer;

class ForgotPasswordProcessorHandlerFactoryTest extends TestCase
{
    #[TestWith([true])]
    #[TestWith([false])]
    public function testCanInstantiateFactory(bool $hasLogger): void
    {
        $container = new InMemoryContainer();
        $container->setService(
            AdapterInterface::class,
            $this->createMock(AdapterInterface::class)
        );
        $container->setService(ForgotPasswordValidator::class, new ForgotPasswordValidator());
        $container->setService(
            EventManagerInterface::class,
            $this->createMock(EventManagerInterface::class)
        );

        if ($hasLogger) {
            $container->setService(
                LoggerInterface::class,
                $this->createMock(LoggerInterface::class)
            );
        }

        $factory = new ForgotPasswordProcessorHandlerFactory();
        $handler = $factory($container);

        self::assertInstanceOf(ForgotPasswordProcessorHandler::class, $handler);
    }
}
