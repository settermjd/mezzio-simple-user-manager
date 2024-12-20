<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\RegisterUser\Adapter;

use GSteel\Dot;
use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

class DefaultAdapterFactory
{
    public function __invoke(ContainerInterface $container): DbAdapter
    {
        $config = $container->get("config");

        $tableName = Dot::stringOrNull(
            "register_user.adapter.db_adapter.table",
            $config
        ) ?? DbAdapter::DEFAULT_TABLE_NAME;

        return new DbAdapter($container->get(AdapterInterface::class), $tableName);
    }
}
