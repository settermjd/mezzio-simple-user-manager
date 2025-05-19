<?php

declare(strict_types=1);


use DateInterval;
use DateTime;
use Phinx\Seed\AbstractSeed;

class PasswordResetsSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $sixDays = (new DateTime())
            ->sub(new DateInterval("P6D"))
            ->format("Y-m-d");
        $oneDay  = (new DateTime())
            ->sub(new DateInterval("P1D"))
            ->format("Y-m-d");
        $data    = [
            [
                'user_identity' => 'matthew@example.org',
                'created_at'    => $sixDays,
            ],
            [
                'user_identity' => 'matthew@example.com',
                'created_at'    => $oneDay,
            ],
        ];

        $table = $this->table('password_resets');
        $table->truncate();
        $table
            ->insert($data)
            ->saveData();
    }
}
