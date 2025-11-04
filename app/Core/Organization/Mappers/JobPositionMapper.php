<?php

declare(strict_types=1);

namespace App\Core\Organization\Mappers;

use App\Core\Organization\DTOs\JobPositionDTO;
use App\Core\Organization\Entities\JobPosition;
use App\Core\Organization\Enums\TierEnum;
use Illuminate\Http\Request;

class JobPositionMapper
{
    public function fromRequest(Request $request): JobPositionDTO
    {
        return new JobPositionDTO(
            title: $request->input('title'),
            code: $request->input('code'),
            tier: $request->input('tier') ? TierEnum::from($request->input('tier')) : null,
            imageUrl: $request->input('image_url'),
            description: $request->input('description'),
            isActive: $request->boolean('is_active', true),
        );
    }

    public function fromRequestForUpdate(Request $request, JobPosition $jobPosition): JobPositionDTO
    {
        return new JobPositionDTO(
            title: $request->input('title') ?? $jobPosition->title,
            code: $request->input('code')   ?? $jobPosition->code,
            tier: $request->input('tier') ? TierEnum::from($request->input('tier')) : $jobPosition->tier,
            imageUrl: $request->input('image_url')      ?? $jobPosition->image_url,
            description: $request->input('description') ?? $jobPosition->description,
            isActive: $request->boolean('is_active', $jobPosition->is_active),
        );
    }
}
