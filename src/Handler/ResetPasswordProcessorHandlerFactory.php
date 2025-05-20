<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use SimpleUserManager\Service\ResetPassword\Adapter\AdapterInterface;
use SimpleUserManager\Validator\ResetPasswordValidator;

class ResetPasswordProcessorHandlerFactory
{
    public function __invoke(ContainerInterface $container): ResetPasswordProcessorHandler
    {
        $logger = $container->has(LoggerInterface::class)
            ? $container->get(LoggerInterface::class)
            : null;

        return new ResetPasswordProcessorHandler(
            $container->get(AdapterInterface::class),
            $container->get(ResetPasswordValidator::class),
            $container->get(EventManagerInterface::class),
            $logger,
        );
    }
}
