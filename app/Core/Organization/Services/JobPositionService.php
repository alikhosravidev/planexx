<?php

declare(strict_types=1);

namespace App\Core\Organization\Services;

use App\Core\Organization\Contracts\JobPositionServiceInterface;
use App\Core\Organization\DTOs\JobPositionDTO;
use App\Core\Organization\Entities\JobPosition;
use App\Core\Organization\Repositories\JobPositionRepository;

class JobPositionService implements JobPositionServiceInterface
{
    public function __construct(
        private readonly JobPositionRepository $jobPositionRepository,
    ) {
    }

    public function create(JobPositionDTO $dto): JobPosition
    {
        $data = $dto->toArray();

        return $this->jobPositionRepository->create($data);
    }

    public function update(JobPosition $jobPosition, JobPositionDTO $dto): JobPosition
    {
        $data = $dto->toArray();

        return $this->jobPositionRepository->update($jobPosition->id, $data);
    }

    public function delete(JobPosition $jobPosition): bool
    {
        return $this->jobPositionRepository->delete($jobPosition->id);
    }
}
