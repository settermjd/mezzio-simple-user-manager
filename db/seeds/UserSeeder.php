<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
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
        $data = [
            [
                'first_name' => 'matthew',
                'last_name'  => 'setter',
                'email'      => 'matthew@example.org',
                'phone'      => '+61123456789',
                'username'   => 'username',
                'password'   => 'password',
            ],
            [
                'first_name' => 'matthew',
                'last_name'  => 'setter',
                'email'      => 'matthew@example.com',
                'phone'      => '+61123456788',
                'username'   => 'username',
                'password'   => 'password',
            ],
            [
                'first_name' => 'matthew',
                'last_name'  => 'setter',
                'email'      => 'matthew@example.net',
                'phone'      => '+61123456787',
                'username'   => 'username',
                'password'   => 'password',
            ],
        ];

        $table = $this->table('user');
        $table->truncate();
        $table
            ->insert($data)
            ->saveData();
    }
}
