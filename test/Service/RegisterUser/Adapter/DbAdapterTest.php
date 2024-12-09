<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\RegisterUser\Adapter;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Mezzio\Authentication\DefaultUser;
use PDO;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Service\RegisterUser\Adapter\DbAdapter;
use tebazil\dbseeder\Seeder;

use function sprintf;

class DbAdapterTest extends TestCase
{
    private Adapter $adapter;

    public function setUp(): void
    {
        $databaseFile = __DIR__ . "/../../../database.sqlite";

        $this->adapter = new Adapter([
            "driver"   => "Pdo_Sqlite",
            "database" => $databaseFile,
        ]);

        $pdo    = new PDO(sprintf('sqlite:%s', $databaseFile));
        $seeder = new Seeder($pdo);
        $seeder
            ->table('user')
            ->data(
                [
                    [1, 'matthew', 'setter', 'matthew@example.org', '+61123456789', 'username', 'password'],
                    [2, 'matthew', 'setter', 'matthew@example.com', '+61123456788', 'username', 'password'],
                    [3, 'matthew', 'setter', 'matthew@example.net', '+61123456787', 'username', 'password'],
                ],
                [false, 'first_name', 'last_name', 'email', 'phone', 'username', 'password']
            )
            ->rowQuantity(3);

        $seeder->refill();
    }

    #[TestWith([
        [
            'first_name' => 'matthew',
            'last_name'  => 'setter',
            'email'      => 'matthew@example.org',
            'phone'      => '+61123456789',
            'username'   => 'username',
            'password'   => 'password',
        ],
        false,
    ])]
    #[TestWith([
        [
            'first_name' => 'matthew',
            'last_name'  => 'setter',
            'email'      => 'user@example.org',
            'phone'      => '+61123456789',
            'username'   => 'username',
            'password'   => 'password',
        ],
        true,
    ])]
    public function testCanRegisterUserAndHandleExceptions(array $details, bool $status): void
    {
        if (! $status) {
            $this->expectException(InvalidQueryException::class);
        }

        $middlewareAdapter = new DbAdapter(adapter: $this->adapter);
        $this->assertSame(
            $status,
            $middlewareAdapter->registerUser(
                new DefaultUser(
                    identity: "",
                    details: [
                        "first_name" => $details["first_name"],
                        "last_name"  => $details["last_name"],
                        "email"      => $details["email"],
                        "phone"      => $details["phone"],
                        "username"   => $details["username"],
                        "password"   => $details["password"],
                    ]
                )
            )
        );
    }
}
