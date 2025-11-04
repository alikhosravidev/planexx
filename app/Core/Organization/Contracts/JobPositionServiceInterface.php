<?php

declare(strict_types=1);

namespace App\Core\Organization\Contracts;

use App\Core\Organization\DTOs\JobPositionDTO;
use App\Core\Organization\Entities\JobPosition;

interface JobPositionServiceInterface
{
    public function create(JobPositionDTO $dto): JobPosition;

    public function update(JobPosition $jobPosition, JobPositionDTO $dto): JobPosition;

    public function delete(JobPosition $jobPosition): bool;
}
