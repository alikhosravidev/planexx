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
