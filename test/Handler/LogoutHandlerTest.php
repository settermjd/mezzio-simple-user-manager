<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SimpleUserManager\Handler\LogoutHandler;

class LogoutHandlerTest extends TestCase
{
    private AuthenticationServiceInterface&MockObject $authService;
    private ServerRequestInterface&MockObject $request;

    public function setUp(): void
    {
        $this->request     = $this->createMock(ServerRequestInterface::class);
        $this->authService = $this->createMock(AuthenticationServiceInterface::class);
    }

    public function testRedirectsToDefaultRouteAfterLogout(): void
    {
        $handler  = new LogoutHandler($this->authService);
        $response = $handler->handle($this->request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame("/", $response->getHeaderLine('Location'));
        $this->assertSame(302, $response->getStatusCode());
    }

    public function testRedirectsToLoginIfAuthServiceIsNull(): void
    {
        $handler  = new LogoutHandler(null);
        $response = $handler->handle($this->request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame("/login", $response->getHeaderLine('Location'));
        $this->assertSame(302, $response->getStatusCode());
    }
}
