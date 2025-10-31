<?php

declare(strict_types=1);

namespace App\Contracts\Requests;

use App\Services\ResponseBuilder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $errors = [];

        foreach ($validator->errors()->toArray() as $field => $messages) {
            foreach ($messages as $message) {
                $errors[] = ['field' => $field, 'message' => trans($message)];
            }
        }

        $this->throwExceptionResponse($errors);
    }

    protected function throwExceptionResponse(array $errors)
    {
        $response = resolve(ResponseBuilder::class)
            ->setErrors($errors)
            ->setMessage(trans('errors.basic_validation_error'))
            ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->build();

        throw new HttpResponseException($response);
    }
}
