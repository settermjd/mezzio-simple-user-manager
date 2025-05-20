<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use SimpleUserManager\Service\RegisterUser\Adapter\AdapterInterface;
use SimpleUserManager\Validator\RegisterUserValidator;

class RegisterUserProcessorHandlerFactory
{
    public function __invoke(ContainerInterface $container): RegisterUserProcessorHandler
    {
        $logger = $container->has(LoggerInterface::class)
            ? $container->get(LoggerInterface::class)
            : null;
        return new RegisterUserProcessorHandler(
            $container->get(AdapterInterface::class),
            $container->get(RegisterUserValidator::class),
            $logger,
        );
    }
}
