<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services;

use App\Services\ResponseBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Mockery;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\UnitTestBase;

class ResponseBuilderTest extends UnitTestBase
{
    private ResponseBuilder $responseBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->responseBuilder = new ResponseBuilder();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function mockResponseFactory(): Mockery\MockInterface
    {
        $mock = Mockery::mock(ResponseFactory::class);
        $this->app->bind(\Illuminate\Contracts\Routing\ResponseFactory::class, function () use ($mock) {
            return $mock;
        });

        return $mock;
    }

    public function test_success_returns_json_response_with_correct_data(): void
    {
        $result  = ['id' => 1, 'name' => 'Test'];
        $message = 'Operation successful';
        $meta    = ['page' => 1, 'per_page' => 10];

        $mockResponse = $this->mockResponseFactory();
        $jsonResponse = new JsonResponse(['status' => true, 'result' => $result, 'message' => $message, 'meta' => $meta], SymfonyResponse::HTTP_OK);
        $mockResponse->shouldReceive('json')
            ->with(['status' => true, 'result' => $result, 'message' => $message, 'meta' => $meta], SymfonyResponse::HTTP_OK)
            ->andReturn($jsonResponse);

        $response = $this->responseBuilder->success($result, $message, $meta);

        $this->assertSame($jsonResponse, $response);
    }

    public function test_success_returns_json_response_with_minimal_data(): void
    {
        $result = ['data' => 'value'];

        $mockResponse = $this->mockResponseFactory();
        $jsonResponse = new JsonResponse(['status' => true, 'result' => $result], SymfonyResponse::HTTP_OK);
        $mockResponse->shouldReceive('json')
            ->with(['status' => true, 'result' => $result], SymfonyResponse::HTTP_OK)
            ->andReturn($jsonResponse);

        $response = $this->responseBuilder->success($result);

        $this->assertSame($jsonResponse, $response);
    }

    public function test_error_returns_json_response_with_full_error_details(): void
    {
        $message    = 'Validation failed';
        $statusCode = SymfonyResponse::HTTP_BAD_REQUEST;
        $errors     = ['email' => 'Email is required'];
        $code       = 'VALIDATION_ERROR';

        $expectedData = [
            'status' => false,
            'error'  => [
                'code'    => $code,
                'message' => $message,
                'details' => $errors,
            ],
        ];

        $mockResponse = $this->mockResponseFactory();
        $jsonResponse = new JsonResponse($expectedData, $statusCode);
        $mockResponse->shouldReceive('json')
            ->with($expectedData, $statusCode)
            ->andReturn($jsonResponse);

        $response = $this->responseBuilder->error($message, $statusCode, $errors, $code);

        $this->assertSame($jsonResponse, $response);
    }

    public function test_error_returns_json_response_with_default_code(): void
    {
        $message    = 'Something went wrong';
        $statusCode = SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR;

        $expectedData = [
            'status' => false,
            'error'  => [
                'code'    => 'error',
                'message' => $message,
            ],
        ];

        $mockResponse = $this->mockResponseFactory();
        $jsonResponse = new JsonResponse($expectedData, $statusCode);
        $mockResponse->shouldReceive('json')
            ->with($expectedData, $statusCode)
            ->andReturn($jsonResponse);

        $response = $this->responseBuilder->error($message, $statusCode);

        $this->assertSame($jsonResponse, $response);
    }

    public function test_created_returns_json_response_with_created_status(): void
    {
        $result  = ['id' => 123, 'name' => 'New Resource'];
        $message = 'Resource created successfully';
        $meta    = ['version' => '1.0'];

        $expectedData = [
            'status'  => true,
            'result'  => $result,
            'message' => $message,
            'meta'    => $meta,
        ];

        $mockResponse = $this->mockResponseFactory();
        $jsonResponse = new JsonResponse($expectedData, SymfonyResponse::HTTP_CREATED);
        $mockResponse->shouldReceive('json')
            ->with($expectedData, SymfonyResponse::HTTP_CREATED)
            ->andReturn($jsonResponse);

        $response = $this->responseBuilder->created($result, $message, $meta);

        $this->assertSame($jsonResponse, $response);
    }

    public function test_build_returns_json_response_with_set_data(): void
    {
        $data = [
            'result'  => ['key' => 'value'],
            'message' => 'Test message',
            'status'  => true,
        ];
        $statusCode = SymfonyResponse::HTTP_OK;

        $mockResponse = $this->mockResponseFactory();
        $jsonResponse = new JsonResponse($data, $statusCode);
        $mockResponse->shouldReceive('json')
            ->with($data, $statusCode)
            ->andReturn($jsonResponse);

        $response = $this->responseBuilder
            ->setResult($data['result'])
            ->setMessage($data['message'])
            ->setStatus($data['status'])
            ->setStatusCode($statusCode)
            ->build();

        $this->assertSame($jsonResponse, $response);
    }

    public function test_set_status_code_sets_status_to_false_when_error_code(): void
    {
        $statusCode = SymfonyResponse::HTTP_BAD_REQUEST;

        $expectedData = [
            'status' => false,
        ];

        $mockResponse = $this->mockResponseFactory();
        $jsonResponse = new JsonResponse($expectedData, $statusCode);
        $mockResponse->shouldReceive('json')
            ->with($expectedData, $statusCode)
            ->andReturn($jsonResponse);

        $response = $this->responseBuilder
            ->setStatusCode($statusCode)
            ->build();

        $this->assertSame($jsonResponse, $response);
    }

    public function test_set_error_sets_error_with_details(): void
    {
        $code    = 'VALIDATION_ERROR';
        $message = 'Invalid input';
        $details = ['field' => 'email', 'reason' => 'required'];

        $expectedData = [
            'error' => [
                'code'    => $code,
                'message' => $message,
                'details' => $details,
            ],
        ];

        $mockResponse = $this->mockResponseFactory();
        $jsonResponse = new JsonResponse($expectedData, SymfonyResponse::HTTP_OK);
        $mockResponse->shouldReceive('json')
            ->with($expectedData, SymfonyResponse::HTTP_OK)
            ->andReturn($jsonResponse);

        $response = $this->responseBuilder
            ->setError($code, $message, $details)
            ->build();

        $this->assertSame($jsonResponse, $response);
    }

    public function test_set_error_sets_error_without_details(): void
    {
        $code    = 'NOT_FOUND';
        $message = 'Resource not found';

        $expectedData = [
            'error' => [
                'code'    => $code,
                'message' => $message,
            ],
        ];

        $mockResponse = $this->mockResponseFactory();
        $jsonResponse = new JsonResponse($expectedData, SymfonyResponse::HTTP_OK);
        $mockResponse->shouldReceive('json')
            ->with($expectedData, SymfonyResponse::HTTP_OK)
            ->andReturn($jsonResponse);

        $response = $this->responseBuilder
            ->setError($code, $message)
            ->build();

        $this->assertSame($jsonResponse, $response);
    }

    public function test_set_errors_sets_errors_array(): void
    {
        $errors = ['email' => ['Email is required'], 'password' => ['Password too short']];

        $expectedData = [
            'errors' => $errors,
        ];

        $mockResponse = $this->mockResponseFactory();
        $jsonResponse = new JsonResponse($expectedData, SymfonyResponse::HTTP_OK);
        $mockResponse->shouldReceive('json')
            ->with($expectedData, SymfonyResponse::HTTP_OK)
            ->andReturn($jsonResponse);

        $response = $this->responseBuilder
            ->setErrors($errors)
            ->build();

        $this->assertSame($jsonResponse, $response);
    }
}
