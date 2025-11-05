<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\DTOs;

use App\Core\Organization\DTOs\JobPositionDTO;
use App\Core\Organization\Enums\TierEnum;
use Tests\UnitTestBase;

class JobPositionDTOTest extends UnitTestBase
{
    public function test_constructor_sets_properties_correctly(): void
    {
        // Arrange
        $title       = 'Senior Developer';
        $code        = 'SD001';
        $tier        = TierEnum::SENIOR_MANAGEMENT;
        $imageUrl    = 'https://example.com/image.jpg';
        $description = 'Handles senior development tasks';
        $isActive    = true;

        // Act
        $dto = new JobPositionDTO(
            title: $title,
            code: $code,
            tier: $tier,
            imageUrl: $imageUrl,
            description: $description,
            isActive: $isActive,
        );

        // Assert
        $this->assertEquals($title, $dto->title);
        $this->assertEquals($code, $dto->code);
        $this->assertEquals($tier, $dto->tier);
        $this->assertEquals($imageUrl, $dto->imageUrl);
        $this->assertEquals($description, $dto->description);
        $this->assertEquals($isActive, $dto->isActive);
    }

    public function test_to_array_returns_correct_array(): void
    {
        // Arrange
        $dto = new JobPositionDTO(
            title: 'Senior Developer',
            code: 'SD001',
            tier: TierEnum::SENIOR_MANAGEMENT,
            imageUrl: 'https://example.com/image.jpg',
            description: 'Handles senior development tasks',
            isActive: true,
        );

        // Act
        $array = $dto->toArray();

        // Assert
        $expected = [
            'title'       => 'Senior Developer',
            'code'        => 'SD001',
            'tier'        => TierEnum::SENIOR_MANAGEMENT->value,
            'image_url'   => 'https://example.com/image.jpg',
            'description' => 'Handles senior development tasks',
            'is_active'   => true,
        ];
        $this->assertEquals($expected, $array);
    }

    public function test_to_array_handles_null_tier(): void
    {
        // Arrange
        $dto = new JobPositionDTO(
            title: 'Developer',
            isActive: false,
        );

        // Act
        $array = $dto->toArray();

        // Assert
        $this->assertNull($array['tier']);
    }

    public function test_constructor_allows_null_values(): void
    {
        // Arrange
        $dto = new JobPositionDTO(
            title: 'Developer',
            isActive: false,
        );

        // Assert
        $this->assertNull($dto->code);
        $this->assertNull($dto->tier);
        $this->assertNull($dto->imageUrl);
        $this->assertNull($dto->description);
        $this->assertFalse($dto->isActive);
    }
}
