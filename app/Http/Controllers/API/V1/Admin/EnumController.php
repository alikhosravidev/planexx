<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Services\EnumService;
use App\Services\ResponseBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class EnumController
{
    public function __construct(
        private readonly ResponseBuilder $response,
        private readonly EnumService     $enumService,
    ) {
    }

    public function show(string $enum): JsonResponse
    {
        try {
            $data = $this->enumService->getData($enum);

            return $this->response->success($data);
        } catch (InvalidArgumentException $exception) {
            return $this->response->failed(trans('errors.requested_enum_invalid'));
        }
    }

    public function keyValList(string $enum, Request $request): JsonResponse
    {
        try {
            $data = $this->enumService->getFilteredKeyValList(
                $enum,
                (string) $request->get('search'),
                'name'
            );

            return $this->response->success($data);
        } catch (InvalidArgumentException $exception) {
            return $this->response->failed(trans('errors.requested_enum_invalid'));
        }
    }
}
