<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\EventManager\EventManagerInterface;
use Laminas\InputFilter\InputFilterInterface;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Handler\ForgotPasswordProcessorHandler;
use SimpleUserManager\Handler\ForgotPasswordProcessorHandlerFactory;
use SimpleUserManager\Service\ForgotPassword\Adapter\AdapterInterface;
use SimpleUserManagerTest\InMemoryContainer;

class ForgotPasswordProcessorHandlerFactoryTest extends TestCase
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
            "ForgotPasswordInputFilter",
            /** @param InputFilterInterface&MockObject */
            $this->createMock(InputFilterInterface::class)
        );
        $container->setService(
            EventManagerInterface::class,
            /** @param EventManagerInterface&MockObject */
            $this->createMock(EventManagerInterface::class)
        );

        $factory = new ForgotPasswordProcessorHandlerFactory();
        $handler = $factory($container);

        self::assertInstanceOf(ForgotPasswordProcessorHandler::class, $handler);
    }
}