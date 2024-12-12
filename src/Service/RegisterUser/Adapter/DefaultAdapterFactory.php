<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\RegisterUser\Adapter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

class DefaultAdapterFactory
{
    public function __invoke(ContainerInterface $container): DbAdapter
    {
        $config = $container->get("config");

        $tableName = $config["register_user"]["adapter"]["db_adapter"]["table"] ?? DbAdapter::DEFAULT_TABLE_NAME;

        return new DbAdapter($container->get(AdapterInterface::class), $tableName);
    }
}
