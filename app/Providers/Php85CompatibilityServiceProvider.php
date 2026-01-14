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
        // Подавляем предупреждения PHP 8.5
        if (PHP_VERSION_ID >= 80500) {
            // Подавляем E_DEPRECATED для PDO и для null offset в Laravel internal
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
     * Динамически исправляем конфигурацию базы данных в памяти.
     */
    protected function patchDatabaseConfig(): void
    {
        $connections = config('database.connections', []);
        $patched = false;

        foreach ($connections as $name => $config) {
            if (isset($config['options']) && is_array($config['options'])) {
                foreach ($config['options'] as $key => $value) {
                    // 1012 - это числовое значение константы PDO::MYSQL_ATTR_SSL_CA
                    if ($key === 'PDO::MYSQL_ATTR_SSL_CA' || $key === 1012) {
                        // В PHP 8.5+ рекомендуется использовать Pdo\Mysql::ATTR_SSL_CA
                        // Но если мы просто хотим убрать предупреждение, мы можем использовать числовое значение
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
