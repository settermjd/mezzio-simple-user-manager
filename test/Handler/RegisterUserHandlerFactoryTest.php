<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Handler\RegisterUserHandler;
use SimpleUserManager\Handler\RegisterUserHandlerFactory;
use SimpleUserManagerTest\InMemoryContainer;

class RegisterUserHandlerFactoryTest extends TestCase
{
    public function testFactoryWithTemplate(): void
    {
        $container = new InMemoryContainer();
        $container->setService(
            TemplateRendererInterface::class,
            $this->createMock(TemplateRendererInterface::class)
        );

        $factory = new RegisterUserHandlerFactory();
        $handler = $factory($container);

        self::assertInstanceOf(RegisterUserHandler::class, $handler);
    }
}
