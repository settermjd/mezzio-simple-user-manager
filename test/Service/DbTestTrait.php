<?php

declare(strict_types=1);

namespace SimpleUserManagerTest\Service;

use Laminas\Db\Adapter\Adapter;
use PasswordResetsSeeder;
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use UserSeeder;

trait DbTestTrait
{
    private static TextWrapper $phinxWrapper;
    private string $databaseFile = __DIR__ . "/../database.sqlite3";

    private function setUpDatabase(): void
    {
        $app = new PhinxApplication();
        $app->setAutoExit(false);
        $app->run(new StringInput(''), new NullOutput());

        self::$phinxWrapper = new TextWrapper($app);
        self::$phinxWrapper->getMigrate("testing");
        self::$phinxWrapper->getSeed(
            seed: [
                UserSeeder::class,
                PasswordResetsSeeder::class,
            ]
        );
    }

    private function getDbAdapter(): Adapter
    {
        return new Adapter([
            "driver"   => "Pdo_Sqlite",
            "database" => $this->databaseFile,
        ]);
    }

    private function tearDownDatabase(): void
    {
        self::$phinxWrapper->getRollback("testing");
    }
}
