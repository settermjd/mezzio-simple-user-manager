<?php

declare(strict_types=1);

namespace SimpleUserManager\Middleware;

use Laminas\Authentication\AuthenticationService;
use Psr\Container\ContainerInterface;
use SimpleUserManager\Middleware\LogoutMiddleware;

class LogoutMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): LogoutMiddleware
    {
        return new LogoutMiddleware($container->get(AuthenticationService::class));
    }
}
