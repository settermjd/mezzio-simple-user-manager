<?php

declare(strict_types=1);

namespace SimpleUserManager\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class UserProfileHandlerFactory
{
    public function __invoke(ContainerInterface $container): UserProfileHandler
    {
        return new UserProfileHandler($container->get(TemplateRendererInterface::class));
    }
}
