<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// PHP 8.5 Compatibility: Suppress deprecation warnings from vendor before Laravel boots
if (PHP_VERSION_ID >= 80500) {
    set_error_handler(function ($errno, $errstr) {
        if ($errno === E_DEPRECATED) {
            if (str_contains($errstr, 'PDO::MYSQL_ATTR_SSL_CA')) return true;
            if (str_contains($errstr, 'Using null as an array offset is deprecated')) return true;
        }
        return false;
    }, E_DEPRECATED);
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
