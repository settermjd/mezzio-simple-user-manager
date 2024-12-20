<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Hydrator\NamingStrategy\MapNamingStrategy;
use Mezzio\Authentication\DefaultUser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleUserManager\Handler\RegisterUserProcessorHandler;
use SimpleUserManager\Service\RegisterUser\Adapter\AdapterInterface;
use SimpleUserManager\Service\RegisterUser\Result;
use SimpleUserManager\Validator\RegisterUserValidator;

class RegisterUserProcessorHandlerTest extends TestCase
{
    /** @var AdapterInterface&MockObject */
    protected $adapter;

    protected function setUp(): void
    {
        $this->adapter = $this->createMock(AdapterInterface::class);
    }

    public function testRedirectsWhenInputIsNotValid()
    {
        /** @var EventManagerInterface&MockObject $eventManager */
        $eventManager = $this->createMock(EventManagerInterface::class);

        $namingStrategy = MapNamingStrategy::createFromHydrationMap(
            [
                'email'      => 'email',
                'password'   => 'password',
                'first_name' => 'firstName',
                'last_name'  => 'lastName',
            ]
        );

        $handler = new RegisterUserProcessorHandler(
            $this->adapter,
            new RegisterUserValidator(),
            $eventManager,
            $namingStrategy
        );

        /** @var ServerRequestInterface&MockObject $request */
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

        /** @var EventManagerInterface&MockObject $eventManager */
        $eventManager = $this->createMock(EventManagerInterface::class);

        $namingStrategy = MapNamingStrategy::createFromHydrationMap(
            [
                'email'      => 'email',
                'password'   => 'password',
                'first_name' => 'firstName',
                'last_name'  => 'lastName',
            ]
        );

        $handler = new RegisterUserProcessorHandler(
            $this->adapter,
            new RegisterUserValidator(),
            $eventManager,
            $namingStrategy
        );

        /** @var ServerRequestInterface&MockObject $request */
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
}
