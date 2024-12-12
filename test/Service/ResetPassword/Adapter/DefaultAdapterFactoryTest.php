<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\ResetPassword\Adapter;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use SimpleUserManager\Service\ResetPassword\Adapter\DbAdapter;
use SimpleUserManager\Service\ResetPassword\Adapter\DefaultAdapterFactory;

class DefaultAdapterFactoryTest extends TestCase
{
    public function testCanInstantiateAdapterFactory()
    {
        /** @var AdapterInterface&Adapter&MockObject $container */
        $dbAdapter = $this->createMock(AdapterInterface::class);

        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->atMost(2))
            ->method("get")
            ->willReturnOnConsecutiveCalls(
                [
                    "reset_password" => [
                        "adapter" => [
                            "db_adapter" => [
                                "table"           => "user",
                                "password_column" => "password",
                                "identity_column" => "email",
                            ],
                        ],
                    ],
                ],
                $dbAdapter,
            );

        $factory = new DefaultAdapterFactory();
        $adapter = $factory->__invoke($container);

        $this->assertInstanceOf(DbAdapter::class, $adapter);
        $this->assertSame("user", $adapter->getTableName());
        $this->assertSame("password", $adapter->getPasswordColumn());
        $this->assertSame("email", $adapter->getIdentityColumn());
    }
}
