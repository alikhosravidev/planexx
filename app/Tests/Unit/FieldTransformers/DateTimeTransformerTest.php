<?php

declare(strict_types=1);

namespace App\Tests\Unit\FieldTransformers;

use App\Services\Transformer\FieldTransformers\DateTimeTransformer;
use Carbon\Carbon;
use InvalidArgumentException;
use Tests\UnitTestBase;

class DateTimeTransformerTest extends UnitTestBase
{
    private DateTimeTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = resolve(DateTimeTransformer::class);
    }

    public function test_transforms_carbon_date_correctly()
    {
        $date = Carbon::create(2023, 10, 15, 14, 30, 0);

        $result = $this->transformer->transform($date);

        $this->assertEquals('2023-10-15 14:30:00', $result['main']);
        $this->assertEquals('2023-10-15', $result['default']);
        $this->assertArrayHasKey('human', $result);
        $this->assertArrayHasKey('gregorian', $result['human']);

        // Check jalali data structure
        $jalali = $result['human'];
        $this->assertArrayHasKey('default', $jalali);
        $this->assertArrayHasKey('short', $jalali);
        $this->assertArrayHasKey('long', $jalali);
        $this->assertArrayHasKey('dayOfWeek', $jalali);
        $this->assertArrayHasKey('month', $jalali);
        $this->assertArrayHasKey('year', $jalali);
        $this->assertArrayHasKey('dayMonth', $jalali);
        $this->assertArrayHasKey('diff', $jalali);

        // Check gregorian data structure
        $gregorian = $result['human']['gregorian'];
        $this->assertArrayHasKey('default', $gregorian);
        $this->assertArrayHasKey('short', $gregorian);
        $this->assertArrayHasKey('long', $gregorian);
        $this->assertArrayHasKey('dayOfWeek', $gregorian);
        $this->assertArrayHasKey('month', $gregorian);
        $this->assertArrayHasKey('year', $gregorian);
        $this->assertArrayHasKey('diff', $gregorian);
    }

    public function test_transforms_string_date_correctly()
    {
        $dateString = '2023-10-15 14:30:00';

        $result = $this->transformer->transform($dateString);

        $this->assertEquals('2023-10-15 14:30:00', $result['main']);
        $this->assertEquals('2023-10-15', $result['default']);
        $this->assertArrayHasKey('human', $result);
    }

    public function test_throws_exception_for_invalid_date_type()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid data type');

        $this->transformer->transform(12345); // Invalid type
    }
}
