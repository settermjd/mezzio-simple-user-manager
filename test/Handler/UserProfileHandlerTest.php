<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\Authentication\AuthenticationServiceInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SimpleUserManager\Entity\User;
use SimpleUserManager\Handler\UserProfileHandler;

class UserProfileHandlerTest extends TestCase
{
    protected AuthenticationServiceInterface&MockObject $authService;
    protected ServerRequestInterface&MockObject $request;
    protected TemplateRendererInterface&MockObject $templateRenderer;

    protected function setUp(): void
    {
        $this->authService      = $this->createMock(AuthenticationServiceInterface::class);
        $this->request          = $this->createMock(ServerRequestInterface::class);
        $this->templateRenderer = $this->createMock(TemplateRendererInterface::class);
    }

    public function testReturnsHtmlResponseWhenTemplateRendererProvided(): void
    {
        $userData = [
            'id'         => 1,
            'first_name' => 'Matthew',
            'last_name'  => 'Setter',
            'email'      => 'matthew@example.org',
            'phone'      => '+610412123456',
        ];

        $user = new User(
            $userData['id'],
            $userData['first_name'],
            $userData['last_name'],
            $userData['email'],
            $userData['phone'],
        );

        $this->templateRenderer
            ->expects($this->once())
            ->method('render')
            ->with('sum-app::user-profile', [
                'user' => $user,
            ])
            ->willReturn('');

        $this->authService
            ->expects($this->once())
            ->method('getIdentity')
            ->willReturn($user);

        $handler = new UserProfileHandler($this->templateRenderer, $this->authService);

        $response = $handler->handle($this->request);

        self::assertInstanceOf(HtmlResponse::class, $response);
    }
}
