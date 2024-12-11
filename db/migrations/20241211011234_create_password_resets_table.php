<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePasswordResetsTable extends AbstractMigration
{
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
