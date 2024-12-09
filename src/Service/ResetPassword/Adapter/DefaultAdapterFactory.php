<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ResetPassword\Adapter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

class DefaultAdapterFactory
{
    public function __invoke(ContainerInterface $container): DbAdapter
    {
        return new DbAdapter($container->get(AdapterInterface::class));
    }
}
