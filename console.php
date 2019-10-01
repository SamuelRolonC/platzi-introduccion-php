#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use App\Commands\CreateUserCommand;
use App\Commands\HelloWorldCommand;
use App\Commands\SendMailCommand;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Console\Application;

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => getenv('DB_DRIVER'),
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => getenv('DB_CHARSET'),
    'collation' => getenv('DB_COLLATION'),
    'prefix'    => getenv('DB_PREFIX'),
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$application = new Application();
$application->add(new HelloWorldCommand());
$application->add(new SendMailCommand());
$application->add(new CreateUserCommand());
$application->run();