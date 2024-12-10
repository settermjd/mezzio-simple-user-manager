<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\ForgotPassword\Adapter;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use SimpleUserManager\Service\ForgotPassword\Adapter\AdapterInterface;
use SimpleUserManager\Service\ForgotPassword\Adapter\DbAdapter;
use SimpleUserManager\Service\ForgotPassword\Adapter\DefaultAdapterFactory;

class DefaultAdapterFactoryTest extends TestCase
{
    public function testCanInstantiateDbAdapterProperly()
    {
        /** @var AdapterInterface&MockObject $container */
        $dbAdapter = $this->createMock(AdapterInterface::class);

        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method("get")
            ->with(AdapterInterface::class)
            ->willReturn($dbAdapter);

        $factory = new DefaultAdapterFactory();
        $adapter = $factory->__invoke($container);

        $this->assertInstanceOf(DbAdapter::class, $adapter);
    }
}
