<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\EventManager\EventManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleUserManager\Handler\ResetPasswordProcessorHandler;
use SimpleUserManager\Service\ResetPassword\Adapter\AdapterInterface;
use SimpleUserManager\Service\ResetPassword\Result;
use SimpleUserManager\Validator\ResetPasswordValidator;

class ResetPasswordProcessorHandlerTest extends TestCase
{
    /** @var AdapterInterface&MockObject */
    protected $adapter;

    protected function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
    }

    public function testRedirectsWhenInputDataIsNotValid(): void
    {
        $email    = "user@";
        $password = "password";

        $this->adapter
            ->expects($this->never())
            ->method('resetPassword');

        /** @var EventManagerInterface&MockObject $eventManager */
        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager
            ->expects($this->never())
            ->method("trigger");

        $handler = new ResetPasswordProcessorHandler(
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

        $response = $handler->handle($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(
            ResetPasswordProcessorHandler::ROUTE_NAME_RESET_PASSWORD,
            $response->getHeaderLine("Location")
        );
    }

    public function testReturnsResponseWhenForgetPasswordSucceedsForUser(): void
    {
        $email    = "user@example.com";
        $password = "password";

        $this->adapter
            ->expects($this->once())
            ->method('resetPassword')
            ->with($email, $password)
            ->willReturn(new Result(Result::SUCCESS));

        /** @var EventManagerInterface&MockObject $eventManager */
        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager
            ->expects($this->once())
            ->method("trigger")
            ->with("reset-password-successful");

        $handler = new ResetPasswordProcessorHandler(
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

        $response = $handler->handle($request);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }
}
