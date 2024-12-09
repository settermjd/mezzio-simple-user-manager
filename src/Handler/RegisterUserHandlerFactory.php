<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class RegisterUserHandlerFactory
{
    public function __invoke(ContainerInterface $container): RegisterUserHandler
    {
        return new RegisterUserHandler($container->get(TemplateRendererInterface::class));
    }
}
