<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Handler\ForgotPasswordHandler;
use SimpleUserManager\Handler\ForgotPasswordHandlerFactory;
use SimpleUserManagerTest\InMemoryContainer;

class ForgotPasswordHandlerFactoryTest extends TestCase
{
    public function testFactoryWithTemplate(): void
    {
        $container = new InMemoryContainer();
        $container->setService(
            TemplateRendererInterface::class,
            $this->createMock(TemplateRendererInterface::class)
        );

        $factory = new ForgotPasswordHandlerFactory();
        $handler = $factory($container);

        self::assertInstanceOf(ForgotPasswordHandler::class, $handler);
    }
}
