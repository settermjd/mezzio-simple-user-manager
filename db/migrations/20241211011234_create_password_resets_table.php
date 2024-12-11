<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePasswordResetsTable extends AbstractMigration
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
        $table = $this->table('password_resets');
        $table
            ->addColumn('user_identity', 'text', ['limit' => 100])
            ->addIndex(
                ['user_identity'],
                [
                    'unique' => true,
                    'name'   => 'idx_passwordresets_user_identity',
                    'order'  => [
                        'user_identity' => 'ASC',
                    ],
                ]
            )
            ->addTimestamps(withTimezone: true)
            ->create();
    }
}
