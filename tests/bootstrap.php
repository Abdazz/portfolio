<?php

/*
|--------------------------------------------------------------------------
| Test Environment Bootstrap
|--------------------------------------------------------------------------
|
| Docker sets real DB_* connection vars on the `app` container's process
| environment (see docker-compose.yml) so it talks to the dev Postgres
| container. Those vars land in $_SERVER, and phpdotenv's adapter chain
| reads $_SERVER before $_ENV/putenv(), so PHPUnit's <env force="true">
| (which only touches $_ENV and putenv()) cannot override them. Without
| this, tests silently run against the real dev database instead of
| the in-memory SQLite configured below.
|
*/

$testEnv = [
    'DB_CONNECTION' => 'sqlite',
    'DB_DATABASE' => ':memory:',
    'DB_HOST' => '',
    'DB_PORT' => '',
    'DB_USERNAME' => '',
    'DB_PASSWORD' => '',
];

foreach ($testEnv as $key => $value) {
    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

require __DIR__.'/../vendor/autoload.php';
