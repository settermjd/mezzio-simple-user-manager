<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Middleware;

use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SimpleUserManager\Middleware\RoutePolicyMiddleware;
use SimpleUserManager\Policy\RedirectFromForgotPasswordPolicy;

class RoutePolicyMiddlewareTest extends TestCase
{
    public function testCanRedirectCorrectly(): void
    {
        $authService = $this->createMock(AuthenticationServiceInterface::class);
        $authService
            ->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(true);

        $routePolicy = new RedirectFromForgotPasswordPolicy($authService, '/user-policy');
        $middleware  = new RoutePolicyMiddleware($routePolicy);

        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);
        /** @var RedirectResponse $response */
        $response = $middleware->process($request, $handler);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/user-policy', $response->getHeaderLine('Location'));
    }

    public function testContinuesWithCurrentRequestIfRedirectIsNotRequired(): void
    {
        $authService = $this->createMock(AuthenticationServiceInterface::class);
        $authService
            ->expects($this->once())
            ->method('hasIdentity')
            ->willReturn(false);

        $routePolicy = new RedirectFromForgotPasswordPolicy($authService, '/user-policy');
        $middleware  = new RoutePolicyMiddleware($routePolicy);

        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);

        $response = $middleware->process($request, $handler);

        $this->assertNotInstanceOf(RedirectResponse::class, $response);
    }
}
