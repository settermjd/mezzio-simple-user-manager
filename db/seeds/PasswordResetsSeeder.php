<?php

declare(strict_types=1);

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
        $today = new DateTime();
        $data  = [
            [
                'user_identity' => 'matthew@example.org',
                'created_at'    => $today->sub(new DateInterval("P6D"))->format("Y-m-d"),
            ],
            [
                'user_identity' => 'matthew@example.com',
                'created_at'    => $today->sub(new DateInterval("P1D"))->format("Y-m-d"),
            ],
        ];

        $table = $this->table('password_resets');
        $table->truncate();
        $table
            ->insert($data)
            ->saveData();
    }
}
