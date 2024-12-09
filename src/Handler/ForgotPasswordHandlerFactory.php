<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ForgotPasswordHandlerFactory
{
    public function __invoke(ContainerInterface $container): ForgotPasswordHandler
    {
        return new ForgotPasswordHandler($container->get(TemplateRendererInterface::class));
    }
}
