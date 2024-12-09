<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Handler\ResetPasswordHandler;
use SimpleUserManager\Handler\ResetPasswordHandlerFactory;
use SimpleUserManagerTest\InMemoryContainer;

class ResetPasswordHandlerFactoryTest extends TestCase
{
    public function testFactoryWithTemplate(): void
    {
        $container = new InMemoryContainer();
        $container->setService(
            TemplateRendererInterface::class,
            $this->createMock(TemplateRendererInterface::class)
        );

        $factory = new ResetPasswordHandlerFactory();
        $handler = $factory($container);

        self::assertInstanceOf(ResetPasswordHandler::class, $handler);
    }
}
