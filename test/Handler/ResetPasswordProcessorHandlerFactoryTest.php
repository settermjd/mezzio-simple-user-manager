<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\EventManager\EventManagerInterface;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Handler\ResetPasswordProcessorHandler;
use SimpleUserManager\Handler\ResetPasswordProcessorHandlerFactory;
use SimpleUserManager\Service\ResetPassword\Adapter\AdapterInterface;
use SimpleUserManager\Validator\ResetPasswordValidator;
use SimpleUserManagerTest\InMemoryContainer;

class ResetPasswordProcessorHandlerFactoryTest extends TestCase
{
    public function testFactoryWithTemplate(): void
    {
        $eventManager = $this->createMock(EventManagerInterface::class);

        $adapter = $this->createMock(AdapterInterface::class);

        $container = new InMemoryContainer();
        $container->setService(AdapterInterface::class, $adapter);
        $container->setService(ResetPasswordValidator::class, new ResetPasswordValidator());
        $container->setService(EventManagerInterface::class, $eventManager);

        $factory = new ResetPasswordProcessorHandlerFactory();
        $handler = $factory($container);

        self::assertInstanceOf(ResetPasswordProcessorHandler::class, $handler);
    }
}
