<?php

declare(strict_types=1);

namespace SimpleUserManager\Middleware;

use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;
use SimpleUserManager\Middleware\ForgotPasswordMiddleware;
use SimpleUserManager\Service\ForgotPassword\Adapter\AdapterInterface;

class ForgotPasswordMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): ForgotPasswordMiddleware
    {
        return new ForgotPasswordMiddleware(
            $container->get(AdapterInterface::class),
            $container->get("ForgotPasswordInputFilter"),
            $container->get(EventManagerInterface::class),
        );
    }
}
