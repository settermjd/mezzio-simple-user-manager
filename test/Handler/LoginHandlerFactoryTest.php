<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Handler\LoginHandler;
use SimpleUserManager\Handler\LoginHandlerFactory;
use SimpleUserManagerTest\InMemoryContainer;

class LoginHandlerFactoryTest extends TestCase
{
    public function testFactoryWithTemplate(): void
    {
        $container = new InMemoryContainer();
        $container->setService(
            TemplateRendererInterface::class,
            $this->createMock(TemplateRendererInterface::class)
        );

        $factory = new LoginHandlerFactory();
        $handler = $factory($container);

        self::assertInstanceOf(LoginHandler::class, $handler);
    }
}
