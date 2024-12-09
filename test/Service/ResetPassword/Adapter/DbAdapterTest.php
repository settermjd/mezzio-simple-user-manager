<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\ResetPassword\Adapter;

use DateInterval;
use DateTime;
use Laminas\Db\Adapter\Adapter;
use PDO;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Exception\PasswordResetNotActiveForUserException;
use SimpleUserManager\Service\ResetPassword\Adapter\DbAdapter;
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

        $today = new DateTime();
        $seeder
            ->table('password_resets')
            ->data(
                [
                    [
                        1,
                        'matthew@example.org',
                        // Set the created date of the password reset to five days in the past
                        $today->sub(new DateInterval("P6D"))->format("Y-m-d"),
                    ],
                    [
                        2,
                        'matthew@example.com',
                        // Set the created date of the password reset to one day in the past
                        "2024-12-08", //$today->sub(new \DateInterval("P1D"))->format("Y-m-d")
                    ],
                ],
                [false, 'user_identity', 'created_on']
            )
            ->rowQuantity(2);

        $seeder->refill();
    }

    #[TestWith(["user@example.com", "password", false])]
    #[TestWith(["matthew@example.org", "password", false])]
    #[TestWith(["matthew@example.com", "password", true])]
    public function testCanSuccessfullyForgetPassword(
        string $userIdentity,
        string $password,
        bool $status
    ): void {
        if (! $status) {
            $this->expectException(PasswordResetNotActiveForUserException::class);
        }

        $middlewareAdapter = new DbAdapter(
            adapter: $this->adapter,
        );

        $this->assertSame($status, $middlewareAdapter->resetPassword($userIdentity, $password));
    }
}
