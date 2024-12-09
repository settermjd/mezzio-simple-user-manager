<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Handler\UserProfileHandler;
use SimpleUserManager\Handler\UserProfileHandlerFactory;
use SimpleUserManagerTest\InMemoryContainer;

class UserProfileHandlerFactoryTest extends TestCase
{
    public function testFactoryWithTemplate(): void
    {
        $container = new InMemoryContainer();
        $container->setService(
            TemplateRendererInterface::class,
            $this->createMock(TemplateRendererInterface::class)
        );

        $factory = new UserProfileHandlerFactory();
        $handler = $factory($container);

        self::assertInstanceOf(UserProfileHandler::class, $handler);
    }
}
