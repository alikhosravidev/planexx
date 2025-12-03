<?php

declare(strict_types=1);

namespace App\Contracts\Transformer;

use App\Contracts\Entity\BaseEntity;
use App\Contracts\Entity\EntityInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Interface for data transformers.
 */
interface TransformerInterface
{
    /**
     * Transform a single model to array format.
     *
     * @param BaseEntity $model
     * @return array
     */
    public function transformModel(EntityInterface $model): array;

    /**
     * Transform an array to array format.
     *
     * @param array $data
     * @return array
     */
    public function transformArray(array $data): array;

    /**
     * Transform a collection of models to array format.
     *
     * @param Collection $models
     * @return array
     */
    public function transformCollection(Collection $models): array;

    /**
     * Set includes for the Fractal manager.
     *
     * @param array $includes
     * @return static
     */
    public function setIncludes(array $includes): static;

    /**
     * Set excludes for the Fractal manager.
     *
     * @param array $excludes
     * @return static
     */
    public function setExcludes(array $excludes): static;

    /**
     * Configure the transformer with request parameters.
     *
     * @param Request $request
     * @return static
     */
    public function withRequest(Request $request): static;
}
