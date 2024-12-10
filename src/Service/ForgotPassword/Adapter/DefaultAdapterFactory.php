<?php

declare(strict_types=1);

namespace SimpleUserManager\Service\ForgotPassword\Adapter;

use Laminas\Db\Adapter\AdapterInterface;
use Psr\Container\ContainerInterface;

class DefaultAdapterFactory
{
    public function __invoke(ContainerInterface $container): DbAdapter
    {
        $config = $container->get("config");
        return new DbAdapter(
            adapter: $container->get(AdapterInterface::class),
            tableName: $config["forgot_password"]["adapter"]["db_adapter"]["table"],
            identityColumn: $config["forgot_password"]["adapter"]["db_adapter"]["identity_column"],
        );
    }
}
