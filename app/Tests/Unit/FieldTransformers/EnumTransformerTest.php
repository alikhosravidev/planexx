<?php

declare(strict_types=1);

namespace App\Tests\Unit\FieldTransformers;

use App\Services\Transformer\FieldTransformers\EnumTransformer;
use Tests\UnitTestBase;

class EnumTransformerTest extends UnitTestBase
{
    private EnumTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = resolve(EnumTransformer::class);
    }

    public function test_transforms_enum_with_label_method_correctly()
    {
        $enum = TestEnumWithLabel::ACTIVE;

        $result = $this->transformer->transform($enum);

        $this->assertEquals('ACTIVE', $result['name']);
        $this->assertEquals('active', $result['value']);
        $this->assertEquals('فعال', $result['label']);
        $this->assertArrayHasKey('cases', $result);
        $this->assertCount(2, $result['cases']);
        $this->assertArrayHasKey('ACTIVE', $result['cases']);
        $this->assertArrayHasKey('INACTIVE', $result['cases']);
    }

    public function test_transforms_enum_without_label_method_correctly()
    {
        $enum = TestEnumWithoutLabel::PENDING;

        $result = $this->transformer->transform($enum);

        $this->assertEquals('PENDING', $result['name']);
        $this->assertEquals('pending', $result['value']);
        $this->assertNull($result['label']);
        $this->assertArrayHasKey('cases', $result);
    }

    public function test_transform_many_enum_cases_correctly()
    {
        $enums = TestEnumWithLabel::cases();

        $result = $this->transformer->transformMany($enums);

        $this->assertCount(2, $result);

        // Check first case
        $this->assertEquals('ACTIVE', $result[0]['name']);
        $this->assertEquals('active', $result[0]['value']);
        $this->assertEquals('فعال', $result[0]['label']);
        $this->assertArrayHasKey('cases', $result[0]);
        $this->assertCount(2, $result[0]['cases']);

        // Check second case
        $this->assertEquals('INACTIVE', $result[1]['name']);
        $this->assertEquals('inactive', $result[1]['value']);
        $this->assertEquals('غیرفعال', $result[1]['label']);
        $this->assertArrayHasKey('cases', $result[1]);
        $this->assertCount(2, $result[1]['cases']);
    }

    public function test_transform_many_with_empty_array_returns_empty_array()
    {
        $result = $this->transformer->transformMany([]);

        $this->assertEquals([], $result);
    }
}

// Test enums for testing purposes
enum TestEnumWithLabel: string
{
    case ACTIVE   = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE   => 'فعال',
            self::INACTIVE => 'غیرفعال',
        };
    }
}

enum TestEnumWithoutLabel: string
{
    case PENDING  = 'pending';
    case APPROVED = 'approved';
}
