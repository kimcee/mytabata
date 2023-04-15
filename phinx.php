<?php

require_once('vendor/autoload.php');
require_once('.environment.php');
require_once('helpers.php');

return [
    'paths' => [
        'migrations' => 'Migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => DB_HOST,
            'name' => DB_NAME,
            'user' => DB_USER,
            'pass' => DB_PASS,
            'port' => 3306,
            'charset' => 'utf8mb4',
        ],
    ],
];
