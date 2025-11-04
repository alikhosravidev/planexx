<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\Mappers;

use App\Core\Organization\Entities\Department;
use App\Core\Organization\Mappers\DepartmentMapper;
use Illuminate\Http\Request;
use Mockery;
use Tests\UnitTestBase;

class DepartmentMapperTest extends UnitTestBase
{
    private DepartmentMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new DepartmentMapper();
    }

    public function test_from_request_maps_all_fields_correctly(): void
    {
        // Arrange
        $requestData = [
            'parent_id'   => 1,
            'name'        => 'Engineering Department',
            'code'        => 'ENG',
            'manager_id'  => 2,
            'image_url'   => 'https://example.com/image.jpg',
            'description' => 'Handles engineering tasks',
            'is_active'   => '1',
        ];
        $request = Request::create('/', 'POST', $requestData);

        // Act
        $dto = $this->mapper->fromRequest($request);

        // Assert
        $this->assertEquals(1, $dto->parentId);
        $this->assertEquals('Engineering Department', $dto->name);
        $this->assertEquals('ENG', $dto->code);
        $this->assertEquals(2, $dto->managerId);
        $this->assertEquals('https://example.com/image.jpg', $dto->imageUrl);
        $this->assertEquals('Handles engineering tasks', $dto->description);
        $this->assertTrue($dto->isActive);
    }

    public function test_from_request_handles_null_values(): void
    {
        // Arrange
        $requestData = [
            'name'      => 'Test Department',
            'is_active' => '0',
        ];
        $request = Request::create('/', 'POST', $requestData);

        // Act
        $dto = $this->mapper->fromRequest($request);

        // Assert
        $this->assertNull($dto->parentId);
        $this->assertNull($dto->code);
        $this->assertNull($dto->managerId);
        $this->assertNull($dto->imageUrl);
        $this->assertNull($dto->description);
        $this->assertFalse($dto->isActive);
    }

    public function test_from_request_for_update_uses_request_values_when_provided(): void
    {
        // Arrange
        $requestData = [
            'name'      => 'Updated Department',
            'is_active' => '1',
        ];
        $request                 = Request::create('/', 'PUT', $requestData);
        $department              = Mockery::mock(Department::class)->makePartial();
        $department->parent_id   = 1;
        $department->name        = 'Old Department';
        $department->code        = 'OLD';
        $department->manager_id  = 2;
        $department->image_url   = 'old.jpg';
        $department->description = 'Old description';
        $department->is_active   = false;

        // Act
        $dto = $this->mapper->fromRequestForUpdate($request, $department);

        // Assert
        $this->assertEquals(1, $dto->parentId); // From entity
        $this->assertEquals('Updated Department', $dto->name); // From request
        $this->assertEquals('OLD', $dto->code); // From entity
        $this->assertEquals(2, $dto->managerId); // From entity
        $this->assertEquals('old.jpg', $dto->imageUrl); // From entity
        $this->assertEquals('Old description', $dto->description); // From entity
        $this->assertTrue($dto->isActive); // From request
    }

    public function test_from_request_for_update_uses_entity_values_when_request_missing(): void
    {
        // Arrange
        $requestData             = [];
        $request                 = Request::create('/', 'PUT', $requestData);
        $department              = Mockery::mock(Department::class)->makePartial();
        $department->parent_id   = 1;
        $department->name        = 'Existing Department';
        $department->code        = 'EXI';
        $department->manager_id  = 2;
        $department->image_url   = 'existing.jpg';
        $department->description = 'Existing description';
        $department->is_active   = true;

        // Act
        $dto = $this->mapper->fromRequestForUpdate($request, $department);

        // Assert
        $this->assertEquals(1, $dto->parentId);
        $this->assertEquals('Existing Department', $dto->name);
        $this->assertEquals('EXI', $dto->code);
        $this->assertEquals(2, $dto->managerId);
        $this->assertEquals('existing.jpg', $dto->imageUrl);
        $this->assertEquals('Existing description', $dto->description);
        $this->assertTrue($dto->isActive);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
