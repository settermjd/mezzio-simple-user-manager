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
    private TemplateRendererInterface&MockObject $templateRenderer;
    private ServerRequestInterface&MockObject $request;

    protected function setUp(): void
    {
        $this->request          = $this->createMock(ServerRequestInterface::class);
        $this->templateRenderer = $this->createMock(TemplateRendererInterface::class);
    }

    public function testReturnsHtmlResponseWhenTemplateRendererProvided(): void
    {
        $this->templateRenderer
            ->expects($this->once())
            ->method('render')
            ->with('sum-app::login', $this->isType('array'))
            ->willReturn('');

        $handler = new LoginHandler($this->templateRenderer);

        $response = $handler->handle($this->request);

        self::assertInstanceOf(HtmlResponse::class, $response);
    }
}
