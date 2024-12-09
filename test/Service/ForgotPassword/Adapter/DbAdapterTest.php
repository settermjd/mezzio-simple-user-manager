<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\ForgotPassword\Adapter;

use DateInterval;
use DateTime;
use Laminas\Db\Adapter\Adapter;
use PDO;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Service\ForgotPassword\Adapter\DbAdapter;
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
                    [1, 'matthew', 'setter', 'matthew@example.com', '+61123456788', 'username', 'password'],
                    [1, 'matthew', 'setter', 'matthew@example.net', '+61123456787', 'username', 'password'],
                ],
                [false, 'first_name', 'last_name', 'email', 'phone', 'username', 'password']
            )
            ->rowQuantity(1);

        $today = new DateTime();
        $seeder
            ->table('password_resets')
            ->data(
                [
                    [
                        'matthew@example.org',
                        // Set the created date of the password reset to five days in the past
                        $today->sub(new DateInterval("P6D"))->format("Y-m-d"),
                    ],
                    [
                        'matthew@example.com',
                        // Set the created date of the password reset to one day in the past
                        $today->sub(new DateInterval("P1D"))->format("Y-m-d"),
                    ],
                ],
                ['user_identity', 'created_on']
            )
            ->rowQuantity(1);

        $seeder->refill();
    }

    #[TestWith(["user@example.com"])]
    #[TestWith(["matthew@example.org"])]
    public function testCanSuccessfullyForgetPassword(string $userIdentity): void
    {
        $middlewareAdapter = new DbAdapter(
            adapter: $this->adapter,
        );

        $this->assertTrue($middlewareAdapter->forgotPassword($userIdentity));
    }
}
