<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SimpleUserManager\Handler\ForgotPasswordHandler;

class ForgotPasswordHandlerTest extends TestCase
{
    protected TemplateRendererInterface&MockObject $templateRenderer;

    protected function setUp(): void
    {
        $this->templateRenderer = $this->createMock(TemplateRendererInterface::class);
    }

    public function testReturnsHtmlResponseWhenTemplateRendererProvided(): void
    {
        $this->templateRenderer
            ->expects($this->once())
            ->method('render')
            ->with('sum-app::forgot-password', $this->isType('array'))
            ->willReturn('');

        $handler = new ForgotPasswordHandler($this->templateRenderer);

        $request  = $this->createMock(ServerRequestInterface::class);
        $response = $handler->handle($request);

        self::assertInstanceOf(HtmlResponse::class, $response);
    }
}
