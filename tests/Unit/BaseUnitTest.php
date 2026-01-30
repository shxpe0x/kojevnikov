<?php

declare(strict_types=1);

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;

abstract class BaseUnitTest extends TestCase
{
    /**
     * Tear down the test environment.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Create a mock object.
     */
    protected function createMock(string $class): Mockery\MockInterface
    {
        return Mockery::mock($class);
    }
}
