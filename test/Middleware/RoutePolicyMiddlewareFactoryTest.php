<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Middleware;

use Laminas\Authentication\AuthenticationServiceInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use SimpleUserManager\Exception\InvalidConfigException;
use SimpleUserManager\Middleware\RoutePolicyMiddlewareFactory;
use SimpleUserManager\Policy\NullRedirectPolicy;
use SimpleUserManager\Policy\RedirectFromForgotPasswordPolicy;

class RoutePolicyMiddlewareFactoryTest extends TestCase
{
    /**
     * @param array<string,array<string,class-string>> $configuration
     * @throws Exception|InvalidConfigException
     */
    #[DataProvider('routePolicyMiddlewareDataProvider')]
    public function testCanInstantiateRoutePolicyMiddlewareUsingConfigurationProvided(
        string $requestedRoute,
        array $configuration,
        string $expectedPolicy,
    ): void {
        $_SERVER['REQUEST_URI'] = $requestedRoute;
        $redirectPolicy         = new RedirectFromForgotPasswordPolicy(
            $this->createMock(AuthenticationServiceInterface::class),
            '/user-profile',
        );

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('has')
            ->with('config')
            ->willReturn(true);
        $container
            ->expects($this->atLeast(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                $configuration,
                $redirectPolicy,
            );

        $middleware = (new RoutePolicyMiddlewareFactory())->__invoke($container);
        $this->assertInstanceOf($expectedPolicy, $middleware->getRoutePolicy());
    }

    /**
     * @return array<string,array<string|class-string|array<string,array<string,class-string>>>>
     */
    public static function routePolicyMiddlewareDataProvider(): array
    {
        return [
            'Simulate a request to the "forgot password" route' => [
                '/forgot-password',
                [
                    'route_policy' => [
                        '/forgot-password' => RedirectFromForgotPasswordPolicy::class,
                        '/user-profile'    => NullRedirectPolicy::class,
                    ],
                ],
                RedirectFromForgotPasswordPolicy::class,
            ],
        ];
    }

    public function testThrowsExceptionIfRoutePolicyConfigIsMissing(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("Missing 'route_policy' key in the application configuration.");

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('has')
            ->with('config')
            ->willReturn(false);
        $middleware = (new RoutePolicyMiddlewareFactory())->__invoke($container);
    }

    public function testReturnsNullRoutePolicyIfCurrentRouteIsNotMatchedInTheRoutePolicyConfig(): void
    {
        $_SERVER['REQUEST_URI'] = '/user-profile';
        $redirectPolicy         = new RedirectFromForgotPasswordPolicy(
            $this->createMock(AuthenticationServiceInterface::class),
            '/user-profile',
        );

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('has')
            ->with('config')
            ->willReturn(true);
        $container
            ->expects($this->atLeast(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                [
                    'route_policy' => [
                        '/forgot-password' => RedirectFromForgotPasswordPolicy::class,
                        '/login'           => NullRedirectPolicy::class,
                        '/logout'          => NullRedirectPolicy::class,
                    ],
                ],
                new NullRedirectPolicy(),
            );

        $middleware = (new RoutePolicyMiddlewareFactory())->__invoke($container);
        $this->assertInstanceOf(NullRedirectPolicy::class, $middleware->getRoutePolicy());
    }
}
