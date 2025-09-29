<?php

use Illuminate\Http\Request;

ini_set('max_execution_time', 120); // 2 minutes

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$response = $app->handle(
    $request = Request::capture()
);

$response->send();

$app->terminate(); // no arguments in Laravel 12
