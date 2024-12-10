<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\ForgotPassword\Adapter;

use Laminas\Db\Adapter\AdapterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use SimpleUserManager\Service\ForgotPassword\Adapter\DbAdapter;
use SimpleUserManager\Service\ForgotPassword\Adapter\DefaultAdapterFactory;

class DefaultAdapterFactoryTest extends TestCase
{
    public function testCanInstantiateDbAdapterWithFullConfiguration()
    {
        /** @var AdapterInterface&MockObject $container */
        $dbAdapter = $this->createMock(AdapterInterface::class);

        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->atMost(2))
            ->method("get")
            ->willReturnOnConsecutiveCalls(
                [
                    "forgot_password" => [
                        "adapter" => [
                            "db_adapter" => [
                                "table" => "password_resets",
                                "identity_column" => "user_id",
                            ]
                        ],
                    ]
                ],
                $dbAdapter,
            );

        $factory = new DefaultAdapterFactory();
        $adapter = $factory->__invoke($container);

        $this->assertInstanceOf(DbAdapter::class, $adapter);
        $this->assertSame("password_resets", $adapter->getTableName());
        $this->assertSame("user_id", $adapter->getIdentityColumn());
    }
}
