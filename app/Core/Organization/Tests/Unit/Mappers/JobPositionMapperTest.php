<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Unit\Mappers;

use App\Core\Organization\Entities\JobPosition;
use App\Core\Organization\Enums\TierEnum;
use App\Core\Organization\Mappers\JobPositionMapper;
use Illuminate\Http\Request;
use Mockery;
use Tests\UnitTestBase;

class JobPositionMapperTest extends UnitTestBase
{
    private JobPositionMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new JobPositionMapper();
    }

    public function test_from_request_maps_all_fields_correctly(): void
    {
        // Arrange
        $requestData = [
            'title'       => 'Senior Developer',
            'code'        => 'SD001',
            'tier'        => 1, // TierEnum::SENIOR_MANAGEMENT
            'image_url'   => 'https://example.com/image.jpg',
            'description' => 'Handles senior development tasks',
            'is_active'   => '1',
        ];
        $request = Request::create('/', 'POST', $requestData);

        // Act
        $dto = $this->mapper->fromRequest($request);

        // Assert
        $this->assertEquals('Senior Developer', $dto->title);
        $this->assertEquals('SD001', $dto->code);
        $this->assertEquals(TierEnum::SENIOR_MANAGEMENT, $dto->tier);
        $this->assertEquals('https://example.com/image.jpg', $dto->imageUrl);
        $this->assertEquals('Handles senior development tasks', $dto->description);
        $this->assertTrue($dto->isActive);
    }

    public function test_from_request_handles_null_values(): void
    {
        // Arrange
        $requestData = [
            'title'     => 'Developer',
            'is_active' => '0',
        ];
        $request = Request::create('/', 'POST', $requestData);

        // Act
        $dto = $this->mapper->fromRequest($request);

        // Assert
        $this->assertNull($dto->code);
        $this->assertNull($dto->tier);
        $this->assertNull($dto->imageUrl);
        $this->assertNull($dto->description);
        $this->assertFalse($dto->isActive);
    }

    public function test_from_request_for_update_uses_request_values_when_provided(): void
    {
        // Arrange
        $requestData = [
            'title'     => 'Updated Position',
            'tier'      => 2, // TierEnum::MIDDLE_MANAGEMENT
            'is_active' => '1',
        ];
        $request                  = Request::create('/', 'PUT', $requestData);
        $jobPosition              = Mockery::mock(JobPosition::class)->makePartial();
        $jobPosition->title       = 'Old Position';
        $jobPosition->code        = 'OLD';
        $jobPosition->tier        = TierEnum::STAFF;
        $jobPosition->image_url   = 'old.jpg';
        $jobPosition->description = 'Old description';
        $jobPosition->is_active   = false;

        // Act
        $dto = $this->mapper->fromRequestForUpdate($request, $jobPosition);

        // Assert
        $this->assertEquals('Updated Position', $dto->title); // From request
        $this->assertEquals('OLD', $dto->code); // From entity
        $this->assertEquals(TierEnum::MIDDLE_MANAGEMENT, $dto->tier); // From request
        $this->assertEquals('old.jpg', $dto->imageUrl); // From entity
        $this->assertEquals('Old description', $dto->description); // From entity
        $this->assertTrue($dto->isActive); // From request
    }

    public function test_from_request_for_update_uses_entity_values_when_request_missing(): void
    {
        // Arrange
        $requestData              = [];
        $request                  = Request::create('/', 'PUT', $requestData);
        $jobPosition              = Mockery::mock(JobPosition::class)->makePartial();
        $jobPosition->title       = 'Existing Position';
        $jobPosition->code        = 'EXI';
        $jobPosition->tier        = TierEnum::INVESTOR;
        $jobPosition->image_url   = 'existing.jpg';
        $jobPosition->description = 'Existing description';
        $jobPosition->is_active   = true;

        // Act
        $dto = $this->mapper->fromRequestForUpdate($request, $jobPosition);

        // Assert
        $this->assertEquals('Existing Position', $dto->title);
        $this->assertEquals('EXI', $dto->code);
        $this->assertEquals(TierEnum::INVESTOR, $dto->tier);
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
