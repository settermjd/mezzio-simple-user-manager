<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\DefaultUser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use SimpleUserManager\Handler\RegisterUserProcessorHandler;
use SimpleUserManager\Service\RegisterUser\Adapter\AdapterInterface;
use SimpleUserManager\Service\RegisterUser\Result;
use SimpleUserManager\Validator\RegisterUserValidator;

class RegisterUserProcessorHandlerTest extends TestCase
{
    protected AdapterInterface&MockObject $adapter;

    protected function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
    }

    public function testRedirectsWhenInputIsNotValid(): void
    {
        $handler = new RegisterUserProcessorHandler($this->adapter, new RegisterUserValidator());

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method("getParsedBody")
            ->willReturn([
                "email"            => "@example.com",
                "password"         => "password",
                "confirm_password" => "password",
                "first_name"       => "Matthew",
                "last_name"        => "Setter",
            ]);

        $response = $handler->handle($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(RegisterUserProcessorHandler::ROUTE_NAME_REGISTER_USER, $response->getHeaderLine("Location"));
    }

    public function testReturnsResponseWhenForgetPasswordSucceedsForUser(): void
    {
        $user = new DefaultUser(
            identity: "",
            details: [
                "email"     => "user@example.com",
                "password"  => "password",
                "firstName" => "Matthew",
                "lastName"  => "Setter",
            ],
        );
        $this->adapter
            ->expects($this->once())
            ->method('registerUser')
            ->with($user)
            ->willReturn(new Result(code: Result::SUCCESS));

        $handler = new RegisterUserProcessorHandler($this->adapter, new RegisterUserValidator());

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method("getParsedBody")
            ->willReturn([
                "email"            => "user@example.com",
                "password"         => "password",
                "confirm_password" => "password",
                "first_name"       => "Matthew",
                "last_name"        => "Setter",
            ]);

        $response = $handler->handle($request);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testCanHandleParsedBodyIsNull(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method("warning")
            ->with("Could not process register user request", [
                'Reason' => 'Request body was null',
            ]);

        $handler = new RegisterUserProcessorHandler(
            $this->adapter,
            new RegisterUserValidator(),
            $logger,
        );

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method("getParsedBody")
            ->willReturn(null);

        $response = $handler->handle($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(RegisterUserProcessorHandler::ROUTE_NAME_REGISTER_USER, $response->getHeaderLine("Location"));
    }
}
