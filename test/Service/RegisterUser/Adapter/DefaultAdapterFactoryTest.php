<?php

namespace SimpleUserManagerTest\Service\RegisterUser\Adapter;

use Laminas\Db\Adapter\AdapterInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use SimpleUserManager\Service\RegisterUser\Adapter\DbAdapter;
use SimpleUserManager\Service\RegisterUser\Adapter\DefaultAdapterFactory;

class DefaultAdapterFactoryTest extends TestCase
{
    public function testCanInstantiateAdapterFactory()
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
                    "register_user" => [
                        "adapter" => [
                            "db_adapter" => [
                                "table" => "user",
                            ]
                        ],
                    ]
                ],
                $dbAdapter,
            );

        $factory = new DefaultAdapterFactory();
        $adapter = $factory->__invoke($container);

        $this->assertInstanceOf(DbAdapter::class, $adapter);
        $this->assertSame("user", $adapter->getTableName());
    }
}
