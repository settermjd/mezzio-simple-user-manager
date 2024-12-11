<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service\ForgotPassword\Adapter;

use JetBrains\PhpStorm\NoReturn;
use Laminas\Db\Adapter\Adapter;
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use SimpleUserManager\Service\ForgotPassword\Adapter\DbAdapter;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class DbAdapterTest extends TestCase
{
    private Adapter $adapter;

    private static TextWrapper $phinxWrapper;

    private string $databaseFile = __DIR__ . "/../../../database.sqlite3";

    private function setUpDatabase(): void
    {
        $app = new PhinxApplication();
        $app->setAutoExit(false);
        $app->run(new StringInput(''), new NullOutput());

        self::$phinxWrapper = new TextWrapper($app);
        self::$phinxWrapper->getMigrate("testing");
        self::$phinxWrapper->getSeed(
            seed: [
                "UserSeeder",
                "PasswordResetsSeeder",
            ]
        );

        $this->adapter = new Adapter([
            "driver"   => "Pdo_Sqlite",
            "database" => $this->databaseFile,
        ]);

        /*$pdo    = new PDO(sprintf('sqlite:%s', $databaseFile));
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
        */
    }

    public function tearDownDatabase(): void
    {
        self::$phinxWrapper->getRollback("testing");
    }

    #[TestWith(["user@example.com"])]
    #[TestWith(["matthew@example.org"])]
    public function testCanSuccessfullyForgetPassword(string $userIdentity): void
    {
        $this->setUpDatabase();

        $middlewareAdapter = new DbAdapter(
            adapter: $this->adapter,
        );

        $this->assertTrue($middlewareAdapter->forgotPassword($userIdentity));

        $this->tearDownDatabase();
    }
}
