<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\Authentication\AuthenticationService;
use Psr\Container\ContainerInterface;

class LogoutHandlerFactory
{
    public function __invoke(ContainerInterface $container): LogoutHandler
    {
        return new LogoutHandler($container->get(AuthenticationService::class));
    }
}
