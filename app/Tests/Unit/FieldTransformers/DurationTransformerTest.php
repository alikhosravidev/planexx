<?php

declare(strict_types=1);

namespace App\Tests\Unit\FieldTransformers;

use App\Services\Transformer\FieldTransformers\DurationTransformer;
use Mockery;
use Tests\UnitTestBase;

class DurationTransformerTest extends UnitTestBase
{
    private DurationTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = resolve(DurationTransformer::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_transforms_duration_in_seconds_correctly()
    {
        $duration = 3661; // 1 hour, 1 minute, 1 second

        $result = $this->transformer->transform($duration);

        $this->assertEquals(3661, $result['seconds']);
        $this->assertEquals(61, $result['minutes']); // floor(3661 / 60)
        $this->assertEquals(1, $result['hours']); // floor(3661 / 3600)
        $this->assertEquals('01:01:01', $result['line_time']);
        $this->assertArrayHasKey('human', $result);
        $this->assertArrayHasKey('long', $result['human']);
        $this->assertArrayHasKey('short', $result['human']);
    }

    public function test_transforms_short_duration_correctly()
    {
        $duration = 125; // 2 minutes, 5 seconds

        $result = $this->transformer->transform($duration);

        $this->assertEquals(125, $result['seconds']);
        $this->assertEquals(2, $result['minutes']);
        $this->assertEquals(0, $result['hours']);
        $this->assertEquals('02:05', $result['line_time']);
        $this->assertEquals('2 دقیقه', $result['human']['short']);
    }

    public function test_transforms_zero_duration_correctly()
    {
        $duration = 0;

        $result = $this->transformer->transform($duration);

        $this->assertEquals(0, $result['seconds']);
        $this->assertEquals(0, $result['minutes']);
        $this->assertEquals(0, $result['hours']);
        $this->assertEquals('00:00', $result['line_time']);
        $this->assertEquals('0 دقیقه', $result['human']['short']);
    }

    public function test_transforms_duration_with_fractions()
    {
        $duration = 3723.5; // 1 hour, 2 minutes, 3.5 seconds

        $result = $this->transformer->transform($duration);

        $this->assertEquals(3723.5, $result['seconds']);
        $this->assertEquals(62, $result['minutes']);
        $this->assertEquals(1, $result['hours']);
        $this->assertEquals('01:02:03', $result['line_time']);
    }
}
