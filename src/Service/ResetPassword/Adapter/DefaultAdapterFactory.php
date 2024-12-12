<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ResetPassword\Adapter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

class DefaultAdapterFactory
{
    public function __invoke(ContainerInterface $container): DbAdapter
    {
        $config = $container->get("config");

        $tableName = $config["reset_password"]["adapter"]["db_adapter"]["table"] ?? DbAdapter::DEFAULT_TABLE_NAME;
        $passwordColumn = $config["reset_password"]["adapter"]["db_adapter"]["password_column"] ?? DbAdapter::DEFAULT_PASSWORD_COLUMN;
        $identityColumn = $config["reset_password"]["adapter"]["db_adapter"]["identity_column"] ?? DbAdapter::DEFAULT_IDENTITY_COLUMN;

        return new DbAdapter(
            $container->get(AdapterInterface::class),
            $tableName,
            $passwordColumn,
            $identityColumn
        );
    }
}
