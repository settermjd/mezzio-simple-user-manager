<?php

declare(strict_types=1);

namespace Settermjd\SimpleUserManagerTest\Datasource;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
use Laminas\Hydrator\ReflectionHydrator;
use PDO;
use PHPUnit\Framework\TestCase;
use Settermjd\SimpleUserManager\Datasource\UserTableGatewayDataSource;
use Settermjd\SimpleUserManager\Entity\User;
use tebazil\dbseeder\Seeder;

use function sprintf;

class UserTableGatewayDataSourceTest extends TestCase
{
    private Adapter $adapter;

    public function setUp(): void
    {
        $this->adapter = new Adapter([
            "driver"   => "Pdo_Sqlite",
            "database" => __DIR__ . "/../database.sqlite",
        ]);

        // Seed the database
        $pdo    = new PDO(sprintf('sqlite:%s', __DIR__ . "/../database.sqlite"));
        $seeder = new Seeder($pdo);

        $data         = [
            [1, 'matthew', 'setter', 'matthew@example.org', '+61123456789', 'username', 'password'],
        ];
        $columnConfig = [false, 'first_name', 'last_name', 'email', 'phone', 'username', 'password'];
        $seeder
            ->table('user')
            ->data($data, $columnConfig)
            ->rowQuantity(1);

        $seeder->refill();
    }

    public function testCanRetrieveUser(): void
    {
        $hydrator = new ReflectionHydrator();
        $hydrator->setNamingStrategy(new UnderscoreNamingStrategy());

        $datasource = new UserTableGatewayDataSource(
            $this->adapter,
            new HydratingResultSet($hydrator, new User())
        );

        $this->assertTrue($datasource->userExists(new User(email: "matthew@example.org")));
    }

    public function testCanRetrieveUserByEmailAddress(): void
    {
        $hydrator = new ReflectionHydrator();
        $hydrator->setNamingStrategy(new UnderscoreNamingStrategy());
        $datasource = new UserTableGatewayDataSource(
            $this->adapter,
            new HydratingResultSet($hydrator, new User())
        );

        $user = $datasource->findByEmailAddress("matthew@example.org");
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame("matthew@example.org", $user->getEmailAddress());
        $this->assertSame("matthew", $user->getFirstName());
    }
}
