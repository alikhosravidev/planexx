<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\DTOs;

use App\Core\Organization\Enums\DepartmentTypeEnum;
use App\Domains\Department\DepartmentDTO;
use Tests\UnitTestBase;

class DepartmentDTOTest extends UnitTestBase
{
    public function test_constructor_sets_properties_correctly(): void
    {
        // Arrange
        $parentId    = 1;
        $name        = 'Engineering Department';
        $code        = 'ENG';
        $managerId   = 2;
        $imageUrl    = 'https://example.com/image.jpg';
        $description = 'Handles engineering tasks';
        $isActive    = true;

        // Act
        $dto = new DepartmentDTO(
            name       : $name,
            parentId   : $parentId,
            code       : $code,
            managerId  : $managerId,
            imageUrl   : $imageUrl,
            description: $description,
            isActive   : $isActive,
        );

        // Assert
        $this->assertEquals($parentId, $dto->parentId);
        $this->assertEquals($name, $dto->name);
        $this->assertEquals($code, $dto->code);
        $this->assertEquals($managerId, $dto->managerId);
        $this->assertEquals($imageUrl, $dto->imageUrl);
        $this->assertEquals($description, $dto->description);
        $this->assertEquals($isActive, $dto->isActive);
    }

    public function test_to_array_returns_correct_array(): void
    {
        // Arrange
        $dto = new DepartmentDTO(
            name       : 'Engineering Department',
            parentId   : 1,
            code       : 'ENG',
            managerId  : 2,
            imageUrl   : 'https://example.com/image.jpg',
            description: 'Handles engineering tasks',
            isActive   : true,
        );

        // Act
        $array = $dto->toArray();

        // Assert
        $expected = [
            'parent_id'   => 1,
            'name'        => 'Engineering Department',
            'code'        => 'ENG',
            'manager_id'  => 2,
            'image_url'   => 'https://example.com/image.jpg',
            'color'       => null,
            'icon'        => null,
            'description' => 'Handles engineering tasks',
            'type'        => DepartmentTypeEnum::DEPARTMENT->value,
            'is_active'   => true,
        ];
        $this->assertEquals($expected, $array);
    }

    public function test_constructor_allows_null_values(): void
    {
        // Arrange
        $dto = new DepartmentDTO(
            name       : 'Test Department',
            isActive   : false,
        );

        // Assert
        $this->assertNull($dto->parentId);
        $this->assertNull($dto->code);
        $this->assertNull($dto->managerId);
        $this->assertNull($dto->imageUrl);
        $this->assertNull($dto->description);
        $this->assertFalse($dto->isActive);
    }
}
