<?php

namespace App\Core;

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();
if ($url = getenv("CLEARDB_DATABASE_URL")) {
    $url = parse_url($url);
    $host = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $database = substr($url["path"], 1);
} else {
    $host = "todo-db";
    $username = "root";
    $password = "root";
    $database = "website";
}

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $host,
    'username' => $username,
    'password' => $password,
    'database' => $database,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
    'port' => '3306'
]);

$capsule->bootEloquent();
