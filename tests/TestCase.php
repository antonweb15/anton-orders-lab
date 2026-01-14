<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    //

    protected function setUp(): void
    {
        if (PHP_VERSION_ID >= 80500) {
            set_error_handler(function ($errno, $errstr) {
                if ($errno === E_DEPRECATED) {
                    if (str_contains($errstr, 'PDO::MYSQL_ATTR_SSL_CA')) return true;
                    if (str_contains($errstr, 'Using null as an array offset is deprecated')) return true;
                }
                return false;
            }, E_DEPRECATED);
        }

        parent::setUp();
    }
}
