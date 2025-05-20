<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use SimpleUserManager\Service\ForgotPassword\Adapter\AdapterInterface;
use SimpleUserManager\Validator\ForgotPasswordValidator;

class ForgotPasswordProcessorHandlerFactory
{
    public function __invoke(ContainerInterface $container): ForgotPasswordProcessorHandler
    {
        $logger = $container->has(LoggerInterface::class)
            ? $container->get(LoggerInterface::class)
            : null;

        return new ForgotPasswordProcessorHandler(
            $container->get(AdapterInterface::class),
            $container->get(ForgotPasswordValidator::class),
            $container->get(EventManagerInterface::class),
            $logger
        );
    }
}
