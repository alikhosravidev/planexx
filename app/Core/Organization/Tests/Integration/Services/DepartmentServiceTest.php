<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Integration\Services;

use App\Core\Organization\Entities\Department;
use App\Core\Organization\Services\DepartmentService;
use App\Domains\Department\DepartmentDTO;
use Tests\IntegrationTestBase;

class DepartmentServiceTest extends IntegrationTestBase
{
    private DepartmentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DepartmentService::class);
    }

    public function test_create_department_successfully(): void
    {
        // Arrange
        $dto = new DepartmentDTO(
            name       : 'Engineering Department',
            code       : 'ENG',
            description: 'Handles engineering tasks',
            isActive   : true,
        );

        // Act
        $department = $this->service->create($dto);

        // Assert
        $this->assertInstanceOf(Department::class, $department);
        $this->assertEquals('Engineering Department', $department->name);
        $this->assertEquals('ENG', $department->code);
        $this->assertEquals('Handles engineering tasks', $department->description);
        $this->assertTrue($department->is_active);
        $this->assertDatabaseHas(Department::class, [
            'id'        => $department->id,
            'name'      => 'Engineering Department',
            'code'      => 'ENG',
            'is_active' => true,
        ]);
    }

    public function test_update_department_successfully(): void
    {
        // Arrange
        $existingDepartment = Department::factory()->create([
            'name'      => 'Old Department',
            'code'      => 'OLD',
            'is_active' => false,
        ]);
        $dto = new DepartmentDTO(
            name       : 'Updated Department',
            code       : 'UPD',
            description: 'Updated description',
            isActive   : true,
        );

        // Act
        $updatedDepartment = $this->service->update($existingDepartment, $dto);

        // Assert
        $this->assertEquals($existingDepartment->id, $updatedDepartment->id);
        $this->assertEquals('Updated Department', $updatedDepartment->name);
        $this->assertEquals('UPD', $updatedDepartment->code);
        $this->assertEquals('Updated description', $updatedDepartment->description);
        $this->assertTrue($updatedDepartment->is_active);
        $this->assertDatabaseHas(Department::class, [
            'id'        => $existingDepartment->id,
            'name'      => 'Updated Department',
            'code'      => 'UPD',
            'is_active' => true,
        ]);
    }

    public function test_delete_department_successfully(): void
    {
        // Arrange
        $department = Department::factory()->create();

        // Act
        $result = $this->service->delete($department);

        // Assert
        $this->assertTrue($result);
        $this->assertSoftDeleted(Department::class, [
            'id' => $department->id,
        ]);
    }

    public function test_delete_nonexistent_department_returns_false(): void
    {
        // Arrange
        $department     = new Department();
        $department->id = 999; // Non-existent ID

        // Act
        $result = $this->service->delete($department);

        // Assert
        $this->assertFalse($result);
    }
}
