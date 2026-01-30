<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

abstract class BaseFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Add common setup logic here
    }

    /**
     * Assert JSON structure matches expected format.
     */
    protected function assertJsonStructure(array $structure, array $responseData): void
    {
        foreach ($structure as $key => $value) {
            if (is_array($value)) {
                $this->assertArrayHasKey($key, $responseData);
                $this->assertJsonStructure($value, $responseData[$key]);
            } else {
                $this->assertArrayHasKey($value, $responseData);
            }
        }
    }
}
