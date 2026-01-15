<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class Php85CompatibilityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Suppress PHP 8.5 warnings
        if (PHP_VERSION_ID >= 80500) {
            // Suppress E_DEPRECATED for PDO and for null offset in Laravel internal
            set_error_handler(function ($errno, $errstr) {
                if ($errno === E_DEPRECATED) {
                    if (str_contains($errstr, 'PDO::MYSQL_ATTR_SSL_CA')) return true;
                    if (str_contains($errstr, 'Using null as an array offset is deprecated')) return true;
                }
                return false;
            }, E_DEPRECATED);

            $this->patchDatabaseConfig();
        }
    }

    /**
     * Dynamically fix database configuration in memory.
     */
    protected function patchDatabaseConfig(): void
    {
        $connections = config('database.connections', []);
        $patched = false;

        foreach ($connections as $name => $config) {
            if (isset($config['options']) && is_array($config['options'])) {
                foreach ($config['options'] as $key => $value) {
                    // 1012 is the numeric value of PDO::MYSQL_ATTR_SSL_CA constant
                    if ($key === 'PDO::MYSQL_ATTR_SSL_CA' || $key === 1012) {
                        // In PHP 8.5+ it's recommended to use Pdo\Mysql::ATTR_SSL_CA
                        // But to remove warnings, we can just use the numeric value
                        unset($connections[$name]['options'][$key]);
                        $connections[$name]['options'][1012] = $value;
                        $patched = true;
                    }
                }
            }
        }

        if ($patched) {
            config(['database.connections' => $connections]);
        }
    }
}
