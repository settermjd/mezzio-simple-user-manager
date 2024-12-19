<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Handler;

use Laminas\Authentication\AuthenticationService;
use Laminas\Diactoros\Response\RedirectResponse;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SimpleUserManager\Handler\LogoutHandler;

class LogoutHandlerTest extends TestCase
{
    public function testRedirectsToDefaultRouteAfterLogout()
    {
        $handler = new LogoutHandler($this->createStub(AuthenticationService::class));
        /** @var ServerRequestInterface&MockObject $request */
        $request  = $this->createMock(ServerRequestInterface::class);
        $response = $handler->handle($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame("/", $response->getHeaderLine('Location'));
        $this->assertSame(302, $response->getStatusCode());
    }
}
