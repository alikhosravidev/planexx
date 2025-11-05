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
        $title       = 'Senior Developer';
        $code        = 'SD001';
        $tier        = TierEnum::SENIOR_MANAGEMENT;
        $imageUrl    = 'https://example.com/image.jpg';
        $description = 'Handles senior development tasks';
        $isActive    = true;

        $requestData = [
            'title'       => $title,
            'code'        => $code,
            'tier'        => $tier->value,
            'image_url'   => $imageUrl,
            'description' => $description,
            'is_active'   => $isActive ? '1' : '0',
        ];
        $request = Request::create('/', 'POST', $requestData);

        // Act
        $dto = $this->mapper->fromRequest($request);

        // Assert
        $this->assertEquals($title, $dto->title);
        $this->assertEquals($code, $dto->code);
        $this->assertEquals($tier, $dto->tier);
        $this->assertEquals($imageUrl, $dto->imageUrl);
        $this->assertEquals($description, $dto->description);
        $this->assertEquals($isActive, $dto->isActive);
    }

    public function test_from_request_handles_null_values(): void
    {
        // Arrange
        $title    = 'Developer';
        $isActive = false;

        $requestData = [
            'title'     => $title,
            'is_active' => $isActive ? '1' : '0',
        ];
        $request = Request::create('/', 'POST', $requestData);

        // Act
        $dto = $this->mapper->fromRequest($request);

        // Assert
        $this->assertNull($dto->code);
        $this->assertNull($dto->tier);
        $this->assertNull($dto->imageUrl);
        $this->assertNull($dto->description);
        $this->assertEquals($isActive, $dto->isActive);
    }

    public function test_from_request_for_update_uses_request_values_when_provided(): void
    {
        // Arrange
        $updatedTitle    = 'Updated Position';
        $updatedTier     = TierEnum::MIDDLE_MANAGEMENT;
        $updatedIsActive = true;
        $oldTitle        = 'Old Position';
        $oldCode         = 'OLD';
        $oldTier         = TierEnum::STAFF;
        $oldImageUrl     = 'old.jpg';
        $oldDescription  = 'Old description';
        $oldIsActive     = false;

        $requestData = [
            'title'     => $updatedTitle,
            'tier'      => $updatedTier->value,
            'is_active' => $updatedIsActive ? '1' : '0',
        ];
        $request                  = Request::create('/', 'PUT', $requestData);
        $jobPosition              = Mockery::mock(JobPosition::class)->makePartial();
        $jobPosition->title       = $oldTitle;
        $jobPosition->code        = $oldCode;
        $jobPosition->tier        = $oldTier;
        $jobPosition->image_url   = $oldImageUrl;
        $jobPosition->description = $oldDescription;
        $jobPosition->is_active   = $oldIsActive;

        // Act
        $dto = $this->mapper->fromRequestForUpdate($request, $jobPosition);

        // Assert
        $this->assertEquals($updatedTitle, $dto->title);
        $this->assertEquals($oldCode, $dto->code);
        $this->assertEquals($updatedTier, $dto->tier);
        $this->assertEquals($oldImageUrl, $dto->imageUrl);
        $this->assertEquals($oldDescription, $dto->description);
        $this->assertEquals($updatedIsActive, $dto->isActive);
    }

    public function test_from_request_for_update_uses_entity_values_when_request_missing(): void
    {
        // Arrange
        $entityTitle       = 'Existing Position';
        $entityCode        = 'EXI';
        $entityTier        = TierEnum::INVESTOR;
        $entityImageUrl    = 'existing.jpg';
        $entityDescription = 'Existing description';
        $entityIsActive    = true;

        $requestData              = [];
        $request                  = Request::create('/', 'PUT', $requestData);
        $jobPosition              = Mockery::mock(JobPosition::class)->makePartial();
        $jobPosition->title       = $entityTitle;
        $jobPosition->code        = $entityCode;
        $jobPosition->tier        = $entityTier;
        $jobPosition->image_url   = $entityImageUrl;
        $jobPosition->description = $entityDescription;
        $jobPosition->is_active   = $entityIsActive;

        // Act
        $dto = $this->mapper->fromRequestForUpdate($request, $jobPosition);

        // Assert
        $this->assertEquals($entityTitle, $dto->title);
        $this->assertEquals($entityCode, $dto->code);
        $this->assertEquals($entityTier, $dto->tier);
        $this->assertEquals($entityImageUrl, $dto->imageUrl);
        $this->assertEquals($entityDescription, $dto->description);
        $this->assertEquals($entityIsActive, $dto->isActive);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
