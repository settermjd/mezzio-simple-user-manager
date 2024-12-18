<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;
use SimpleUserManager\Service\ForgotPassword\Adapter\AdapterInterface;

class ForgotPasswordProcessorHandlerFactory
{
    public function __invoke(ContainerInterface $container): ForgotPasswordProcessorHandler
    {
        return new ForgotPasswordProcessorHandler(
            $container->get(AdapterInterface::class),
            $container->get("ForgotPasswordInputFilter"),
            $container->get(EventManagerInterface::class),
        );
    }
}
