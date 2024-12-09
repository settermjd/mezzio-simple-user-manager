<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Middleware;

use Laminas\EventManager\EventManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SimpleUserManager\Middleware\ResetPasswordMiddleware;
use SimpleUserManager\Service\ResetPassword\Adapter\AdapterInterface;
use SimpleUserManager\Validator\ResetPasswordValidator;

class ResetPasswordMiddlewareTest extends TestCase
{
    /** @var AdapterInterface&MockObject */
    protected $adapter;

    protected function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
    }

    public function testReturnsResponseWhenForgetPasswordSucceedsForUser(): void
    {
        $email    = "user@example.com";
        $password = "password";

        $this->adapter
            ->expects($this->once())
            ->method('resetPassword')
            ->with($email, $password)
            ->willReturn(true);

        /** @var EventManagerInterface&MockObject $eventManager */
        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager
            ->expects($this->once())
            ->method("trigger")
            ->with("reset-password-successful");

        $middleware = new ResetPasswordMiddleware(
            $this->adapter,
            new ResetPasswordValidator(),
            $eventManager
        );

        /** @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method("getParsedBody")
            ->willReturn([
                "email"            => $email,
                "password"         => $password,
                "confirm_password" => $password,
            ]);

        /** @var RequestHandlerInterface&MockObject $handler */
        $handler = $this->createMock(RequestHandlerInterface::class);

        $response = $middleware->process($request, $handler);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }
}
