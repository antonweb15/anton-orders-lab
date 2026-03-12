<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class SimpleFeatureTest extends TestCase
{
    public function testBasicFeature()
    {
        // Пример простой логики
        $x = 10;
        $y = 5;
        $result = $x - $y;

        // Проверяем результат
        $this->assertEquals(
            5,
            $result,
            "Feature test failed: 10 - 5 should equal 5"
        );

        // Выводим понятное сообщение в CI
        echo "\nTest passed: Basic subtraction feature works correctly.\n";
    }
}
