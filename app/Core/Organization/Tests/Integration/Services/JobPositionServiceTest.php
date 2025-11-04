<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Integration\Services;

use App\Core\Organization\DTOs\JobPositionDTO;
use App\Core\Organization\Entities\JobPosition;
use App\Core\Organization\Enums\TierEnum;
use App\Core\Organization\Services\JobPositionService;
use Tests\IntegrationTestBase;

class JobPositionServiceTest extends IntegrationTestBase
{
    private JobPositionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(JobPositionService::class);
    }

    public function test_create_job_position_successfully(): void
    {
        // Arrange
        $dto = new JobPositionDTO(
            title: 'Senior Developer',
            code: 'SD001',
            tier: TierEnum::SENIOR_MANAGEMENT,
            imageUrl: null,
            description: 'Handles senior development tasks',
            isActive: true,
        );

        // Act
        $jobPosition = $this->service->create($dto);

        // Assert
        $this->assertInstanceOf(JobPosition::class, $jobPosition);
        $this->assertEquals('Senior Developer', $jobPosition->title);
        $this->assertEquals('SD001', $jobPosition->code);
        $this->assertEquals(TierEnum::SENIOR_MANAGEMENT, $jobPosition->tier);
        $this->assertEquals('Handles senior development tasks', $jobPosition->description);
        $this->assertTrue($jobPosition->is_active);
        $this->assertDatabaseHas(JobPosition::class, [
            'id'        => $jobPosition->id,
            'title'     => 'Senior Developer',
            'code'      => 'SD001',
            'tier'      => 1, // TierEnum::SENIOR_MANAGEMENT->value
            'is_active' => true,
        ]);
    }

    public function test_update_job_position_successfully(): void
    {
        // Arrange
        $existingJobPosition = JobPosition::factory()->create([
            'title'     => 'Old Position',
            'code'      => 'OLD',
            'tier'      => TierEnum::STAFF,
            'is_active' => false,
        ]);
        $dto = new JobPositionDTO(
            title: 'Updated Position',
            code: 'UPD',
            tier: TierEnum::MIDDLE_MANAGEMENT,
            imageUrl: null,
            description: 'Updated description',
            isActive: true,
        );

        // Act
        $updatedJobPosition = $this->service->update($existingJobPosition, $dto);

        // Assert
        $this->assertEquals($existingJobPosition->id, $updatedJobPosition->id);
        $this->assertEquals('Updated Position', $updatedJobPosition->title);
        $this->assertEquals('UPD', $updatedJobPosition->code);
        $this->assertEquals(TierEnum::MIDDLE_MANAGEMENT, $updatedJobPosition->tier);
        $this->assertEquals('Updated description', $updatedJobPosition->description);
        $this->assertTrue($updatedJobPosition->is_active);
        $this->assertDatabaseHas(JobPosition::class, [
            'id'        => $existingJobPosition->id,
            'title'     => 'Updated Position',
            'code'      => 'UPD',
            'tier'      => 2, // TierEnum::MIDDLE_MANAGEMENT->value
            'is_active' => true,
        ]);
    }

    public function test_delete_job_position_successfully(): void
    {
        // Arrange
        $jobPosition = JobPosition::factory()->create();

        // Act
        $result = $this->service->delete($jobPosition);

        // Assert
        $this->assertTrue($result);
        $this->assertSoftDeleted(JobPosition::class, [
            'id' => $jobPosition->id,
        ]);
    }

    public function test_delete_nonexistent_job_position_returns_false(): void
    {
        // Arrange
        $jobPosition     = new JobPosition();
        $jobPosition->id = 999; // Non-existent ID

        // Act
        $result = $this->service->delete($jobPosition);

        // Assert
        $this->assertFalse($result);
    }
}
