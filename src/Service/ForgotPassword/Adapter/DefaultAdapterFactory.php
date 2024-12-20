<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ForgotPassword\Adapter;

use GSteel\Dot;
use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

class DefaultAdapterFactory
{
    public function __invoke(ContainerInterface $container): DbAdapter
    {
        $config = $container->get("config");

        $tableName = Dot::stringOrNull(
            "forgot_password.adapter.db_adapter.table",
            $config
        ) ?? DbAdapter::DEFAULT_TABLE_NAME;
        $identityColumn = Dot::stringOrNull(
            "forgot_password.adapter.db_adapter.identity_column",
            $config
        ) ?? DbAdapter::DEFAULT_IDENTITY_COLUMN;

        return new DbAdapter(
            adapter: $container->get(AdapterInterface::class),
            tableName: $tableName,
            identityColumn: $identityColumn,
        );
    }
}
