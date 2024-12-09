<?php

declare(strict_types=1);

namespace SimpleUserManager\Middleware;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Hydrator\NamingStrategy\NamingStrategyInterface;
use Psr\Container\ContainerInterface;
use SimpleUserManager\Service\RegisterUser\Adapter\AdapterInterface;
use SimpleUserManager\Validator\RegisterUserValidator;

class RegisterUserMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): RegisterUserMiddleware
    {
        return new RegisterUserMiddleware(
            $container->get(AdapterInterface::class),
            $container->get(RegisterUserValidator::class),
            $container->get(EventManagerInterface::class),
            $container->get(NamingStrategyInterface::class),
        );
    }
}
