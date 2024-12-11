<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUserTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('user');
        $table
            ->addColumn('first_name', 'text', ['limit' => 100])
            ->addColumn('last_name', 'text', ['limit' => 100])
            ->addColumn('email', 'text', ['limit' => 100, 'null' => false])
            ->addColumn('phone', 'text', ['limit' => 15])
            ->addColumn('username', 'text', ['limit' => 100, 'null' => false])
            ->addColumn('password', 'text', ['limit' => 30, 'null' => false])
            ->addIndex(
                ['email', 'username', 'first_name', 'last_name'],
                [
                    'unique' => true,
                    'name'   => 'idx_user_unique_user',
                    'order'  => [
                        'email'      => 'ASC',
                        'username'   => 'ASC',
                        'first_name' => 'ASC',
                        'last_name'  => 'ASC',
                    ],
                ]
            )
            ->addTimestamps(withTimezone: true)
            ->create();
    }
}
