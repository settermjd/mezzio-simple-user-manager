<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class ResetPasswordHandlerFactory
{
    public function __invoke(ContainerInterface $container): ResetPasswordHandler
    {
        return new ResetPasswordHandler($container->get(TemplateRendererInterface::class));
    }
}
