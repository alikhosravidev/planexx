<?php

declare(strict_types=1);

namespace App\Core\Organization\Services;

use App\Core\Organization\Contracts\JobPositionServiceInterface;
use App\Core\Organization\DTOs\JobPositionDTO;
use App\Core\Organization\Entities\JobPosition;
use App\Core\Organization\Repositories\JobPositionRepository;

readonly class JobPositionService implements JobPositionServiceInterface
{
    public function __construct(
        private JobPositionRepository $jobPositionRepository,
    ) {
    }

    public function create(JobPositionDTO $dto): JobPosition
    {
        return $this->jobPositionRepository->create($dto->toArray());
    }

    public function update(JobPosition $jobPosition, JobPositionDTO $dto): JobPosition
    {
        return $this->jobPositionRepository->update($jobPosition->id, $dto->toArray());
    }

    public function delete(JobPosition $jobPosition): bool
    {
        return $this->jobPositionRepository->delete($jobPosition->id);
    }
}
