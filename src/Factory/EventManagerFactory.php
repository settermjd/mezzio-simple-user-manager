<?php

declare(strict_types=1);

namespace SimpleUserManager\Factory;

use Laminas\EventManager\EventManager;
use Laminas\EventManager\EventManagerInterface;
use Psr\Container\ContainerInterface;

class EventManagerFactory
{
    public function __invoke(ContainerInterface $container): EventManagerInterface
    {
        return new EventManager();
    }
}
