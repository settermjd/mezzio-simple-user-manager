<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\EventManager\EventManagerInterface;
use Mezzio\Authentication\DefaultUser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SimpleUserManager\Handler\ForgotPasswordProcessorHandler;
use SimpleUserManager\Service\ForgotPassword\Adapter\AdapterInterface;
use SimpleUserManager\Service\ForgotPassword\Result;
use SimpleUserManager\Validator\ForgotPasswordValidator;

class ForgotPasswordProcessorHandlerTest extends TestCase
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
            ->willReturn(new Result(Result::SUCCESS));

        /** @var EventManagerInterface&MockObject $eventManager */
        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager
            ->expects($this->once())
            ->method("trigger")
            ->with("forgot-message-success", new DefaultUser(identity: "", details: [
                "email" => $userIdentity,
            ]));

        $handler = new ForgotPasswordProcessorHandler(
            $this->adapter,
            new ForgotPasswordValidator(),
            $eventManager,
        );

        /** @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method("getParsedBody")
            ->willReturn([
                "email" => $userIdentity,
            ]);

        $response = $handler->handle($request);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testFiresFailEventWhenForgetPasswordFailsForUser(): void
    {
        $userIdentity = "user@example.com";
        $this->adapter
            ->expects($this->once())
            ->method('forgotPassword')
            ->with($userIdentity)
            ->willReturn(new Result(Result::FAILURE_RECORD_EXISTS_FOR_PROVIDED_IDENTITY));

        /** @var EventManagerInterface&MockObject $eventManager */
        $eventManager = $this->createMock(EventManagerInterface::class);
        $eventManager
            ->expects($this->once())
            ->method("trigger")
            ->with("forgot-message-fail", new DefaultUser(identity: "", details: [
                "email" => $userIdentity,
            ]));

        $handler = new ForgotPasswordProcessorHandler(
            $this->adapter,
            new ForgotPasswordValidator(),
            $eventManager,
        );

        /** @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method("getParsedBody")
            ->willReturn([
                "email" => $userIdentity,
            ]);

        $response = $handler->handle($request);

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

        /** @var EventManagerInterface&MockObject $eventManager */
        $eventManager = $this->createMock(EventManagerInterface::class);

        $handler = new ForgotPasswordProcessorHandler($this->adapter, $inputFilter, $eventManager);

        /** @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method("getParsedBody")
            ->willReturn([
                "email" => $userIdentity,
            ]);

        $response = $handler->handle($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(
            $response->getHeaderLine("Location"),
            ForgotPasswordProcessorHandler::ROUTE_NAME_FORGOT_PASSWORD
        );
    }
}
