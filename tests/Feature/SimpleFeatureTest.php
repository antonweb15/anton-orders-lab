<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class SimpleFeatureTest extends TestCase
{
    public function test_basic_feature()
    {
        // Simple logic example
        $x = 10;
        $y = 5;
        $result = $x - $y;

        // Check the result
        $this->assertEquals(
            5,
            $result,
            'Feature test failed: 10 - 5 should equal 5'
        );

        // Print a clear message in CI
        echo "\nTest passed: Basic subtraction feature works correctly.\n";
    }
}
