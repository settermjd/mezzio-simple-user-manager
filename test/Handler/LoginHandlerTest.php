<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SimpleUserManager\Handler\LoginHandler;

class LoginHandlerTest extends TestCase
{
    /** @var TemplateRendererInterface&MockObject */
    protected $templateRenderer;

    protected function setUp(): void
    {
        $this->templateRenderer = $this->createMock(TemplateRendererInterface::class);
    }

    public function testReturnsHtmlResponseWhenTemplateRendererProvided(): void
    {
        $this->templateRenderer
            ->expects($this->once())
            ->method('render')
            ->with('app::login', $this->isType('array'))
            ->willReturn('');

        $handler = new LoginHandler($this->templateRenderer);

        $response = $handler->handle(
            /** @param ServerRequestInterface&MockeObject */
            $this->createMock(ServerRequestInterface::class)
        );

        self::assertInstanceOf(HtmlResponse::class, $response);
    }
}