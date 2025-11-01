<?php

namespace App\Core\User\Services;

use App\Core\User\Entities\City;
use App\Core\User\Repositories\CityRepository;
use App\Core\User\Services\DTOs\GetCitiesDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CityService
{
    public function __construct(
        private readonly CityRepository $cityRepository,
    ) {}

    public function getCities(GetCitiesDTO $dto, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->cityRepository->query()
            ->with(['province.country']);

        if ($dto->search) {
            $query->where('name', 'like', "%{$dto->search}%");
        }

        $orderBy = $dto->orderBy === 'asc' ? 'asc' : 'desc';
        $query->orderBy('id', $orderBy);

        return $query->paginate($perPage);
    }

    public function getCity(int $id): City
    {
        return $this->cityRepository->query()
            ->with(['province.country'])
            ->findOrFail($id);
    }
}
