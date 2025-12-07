<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\Mappers;

use App\Core\Organization\Entities\Department;
use App\Core\Organization\Enums\DepartmentTypeEnum;
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
        $parentId    = 1;
        $name        = 'Engineering Department';
        $code        = 'ENG';
        $managerId   = 2;
        $description = 'Handles engineering tasks';
        $isActive    = true;

        $requestData = [
            'parent_id'   => $parentId,
            'name'        => $name,
            'code'        => $code,
            'manager_id'  => $managerId,
            'description' => $description,
            'is_active'   => $isActive ? '1' : '0',
        ];
        $request = Request::create('/', 'POST', $requestData);

        // Act
        $dto = $this->mapper->fromRequest($request);

        // Assert
        $this->assertEquals($parentId, $dto->parentId);
        $this->assertEquals($name, $dto->name);
        $this->assertEquals($code, $dto->code);
        $this->assertEquals($managerId, $dto->managerId);
        $this->assertEquals($description, $dto->description);
        $this->assertEquals($isActive, $dto->isActive);
    }

    public function test_from_request_handles_null_values(): void
    {
        // Arrange
        $name     = 'Test Department';
        $isActive = false;

        $requestData = [
            'name'      => $name,
            'is_active' => $isActive ? '1' : '0',
        ];
        $request = Request::create('/', 'POST', $requestData);

        // Act
        $dto = $this->mapper->fromRequest($request);

        // Assert
        $this->assertNull($dto->parentId);
        $this->assertNull($dto->code);
        $this->assertNull($dto->managerId);
        $this->assertNull($dto->description);
        $this->assertEquals($isActive, $dto->isActive);
    }

    public function test_from_request_for_update_uses_request_values_when_provided(): void
    {
        // Arrange
        $updatedName       = 'Updated Department';
        $updatedIsActive   = true;
        $entityParentId    = 1;
        $entityName        = 'Old Department';
        $entityCode        = 'OLD';
        $entityManagerId   = 2;
        $entityDescription = 'Old description';
        $entityIsActive    = false;

        $requestData = [
            'name'      => $updatedName,
            'is_active' => $updatedIsActive ? '1' : '0',
        ];
        $request                 = Request::create('/', 'PUT', $requestData);
        $department              = Mockery::mock(Department::class)->makePartial();
        $department->parent_id   = $entityParentId;
        $department->name        = $entityName;
        $department->code        = $entityCode;
        $department->manager_id  = $entityManagerId;
        $department->description = $entityDescription;
        $department->type        = DepartmentTypeEnum::DEPARTMENT;
        $department->is_active   = $entityIsActive;

        // Act
        $dto = $this->mapper->fromRequestForUpdate($request, $department);

        // Assert
        $this->assertEquals($entityParentId, $dto->parentId); // From entity
        $this->assertEquals($updatedName, $dto->name); // From request
        $this->assertEquals($entityCode, $dto->code); // From entity
        $this->assertEquals($entityManagerId, $dto->managerId); // From entity
        $this->assertEquals($entityDescription, $dto->description); // From entity
        $this->assertEquals($updatedIsActive, $dto->isActive); // From request
    }

    public function test_from_request_for_update_uses_entity_values_when_request_missing(): void
    {
        // Arrange
        $entityParentId    = 1;
        $entityName        = 'Existing Department';
        $entityCode        = 'EXI';
        $entityManagerId   = 2;
        $entityDescription = 'Existing description';
        $entityIsActive    = true;

        $requestData             = [];
        $request                 = Request::create('/', 'PUT', $requestData);
        $department              = Mockery::mock(Department::class)->makePartial();
        $department->parent_id   = $entityParentId;
        $department->name        = $entityName;
        $department->code        = $entityCode;
        $department->manager_id  = $entityManagerId;
        $department->description = $entityDescription;
        $department->type        = DepartmentTypeEnum::DEPARTMENT;
        $department->is_active   = $entityIsActive;

        // Act
        $dto = $this->mapper->fromRequestForUpdate($request, $department);

        // Assert
        $this->assertEquals($entityParentId, $dto->parentId);
        $this->assertEquals($entityName, $dto->name);
        $this->assertEquals($entityCode, $dto->code);
        $this->assertEquals($entityManagerId, $dto->managerId);
        $this->assertEquals($entityDescription, $dto->description);
        $this->assertEquals($entityIsActive, $dto->isActive);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
