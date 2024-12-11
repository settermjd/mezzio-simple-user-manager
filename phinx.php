<?php

declare(strict_types=1);

return [
    'paths'         => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds'      => '%%PHINX_CONFIG_DIR%%/db/seeds',
    ],
    'environments'  => [
        'default_migration_table' => 'phinxlog',
        'default_environment'     => 'testing',
        'production'              => [
            'adapter' => 'sqlite',
            'name'    => './db/database',
        ],
        'development'             => [
            'adapter' => 'sqlite',
            'host'    => 'localhost',
            'name'    => 'development_db',
            'user'    => 'root',
            'pass'    => '',
            'port'    => '3306',
            'charset' => 'utf8',
        ],
        'testing'                 => [
            'adapter' => 'sqlite',
            'name'    => './test/database',
        ],
    ],
    'version_order' => 'creation',
];
