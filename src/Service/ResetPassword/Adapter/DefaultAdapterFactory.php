<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ResetPassword\Adapter;

use GSteel\Dot;
use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

class DefaultAdapterFactory
{
    public function __invoke(ContainerInterface $container): DbAdapter
    {
        $config = $container->get("config");

        $tableName = Dot::stringOrNull(
            "reset_password.adapter.db_adapter.table",
            $config
        ) ?? DbAdapter::DEFAULT_TABLE_NAME;
        $passwordColumn = Dot::stringOrNull(
            "reset_password.adapter.db_adapter.password_column",
            $config
        ) ?? DbAdapter::DEFAULT_PASSWORD_COLUMN;
        $identityColumn = Dot::stringOrNull(
            "reset_password.adapter.db_adapter.identity_column",
            $config
        ) ?? DbAdapter::DEFAULT_IDENTITY_COLUMN;

        return new DbAdapter(
            $container->get(AdapterInterface::class),
            $tableName,
            $passwordColumn,
            $identityColumn
        );
    }
}
