<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Middleware;

use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SimpleUserManager\Middleware\ForgotPasswordMiddleware;
use SimpleUserManager\Service\ForgotPassword\Adapter\AdapterInterface;
use SimpleUserManager\Validator\ForgotPasswordValidator;

class ForgotPasswordMiddlewareTest extends TestCase
{
    /** @var AdapterInterface&MockObject */
    protected $adapter;

    protected function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
    }

    public function testReturnsResponseWhenForgetPasswordSucceedsForUser(): void
    {
        $userIdentity = "user@example.com";
        $this->adapter
            ->expects($this->once())
            ->method('forgotPassword')
            ->with($userIdentity)
            ->willReturn(true);

        $user = $this->createMock(UserInterface::class);
        $user
            ->expects($this->once())
            ->method("getIdentity")
            ->willReturn($userIdentity);

        $middleware = new ForgotPasswordMiddleware($this->adapter, new ForgotPasswordValidator());

        /** @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method("getAttribute")
            ->with(UserInterface::class)
            ->willReturn($user);
        $request
            ->expects($this->once())
            ->method("getParsedBody")
            ->willReturn([
                "email" => $userIdentity,
            ]);

        /** @var RequestHandlerInterface&MockObject $handler */
        $handler = $this->createMock(RequestHandlerInterface::class);

        $response = $middleware->process($request, $handler);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testRedirectsToForgetPasswordHandlerWhenForgetPasswordValidatorIsNotValid(): void
    {
        $userIdentity = "user@example.com";

        /** @var ForgotPasswordValidator&MockObject $inputFilter */
        $inputFilter = $this->createMock(ForgotPasswordValidator::class);
        $inputFilter
            ->expects($this->once())
            ->method("isValid")
            ->willReturn(false);

        $middleware = new ForgotPasswordMiddleware($this->adapter, $inputFilter);

        /** @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method("getParsedBody")
            ->willReturn([
                "email" => $userIdentity,
            ]);

        /** @var RequestHandlerInterface&MockObject $handler */
        $handler = $this->createMock(RequestHandlerInterface::class);

        $response = $middleware->process($request, $handler);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(
            $response->getHeaderLine("Location"),
            ForgotPasswordMiddleware::ROUTE_NAME_FORGOT_PASSWORD
        );
    }
}
