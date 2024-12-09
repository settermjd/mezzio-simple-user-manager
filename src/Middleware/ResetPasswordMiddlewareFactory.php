<?php

declare(strict_types=1);

namespace SimpleUserManager\Middleware;

use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;
use SimpleUserManager\Middleware\ResetPasswordMiddleware;
use SimpleUserManager\Service\ResetPassword\Adapter\AdapterInterface;
use SimpleUserManager\Validator\ResetPasswordValidator;

class ResetPasswordMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): ResetPasswordMiddleware
    {
        return new ResetPasswordMiddleware(
            $container->get(AdapterInterface::class),
            $container->get(ResetPasswordValidator::class),
            $container->get(EventManagerInterface::class)
        );
    }
}
