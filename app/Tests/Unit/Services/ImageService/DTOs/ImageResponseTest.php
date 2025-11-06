<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\ImageService\DTOs;

use App\Services\AIImageService\DTOs\ImageResponse;
use Tests\PureUnitTestBase;

class ImageResponseTest extends PureUnitTestBase
{
    public function test_constructs_successfully_with_all_parameters(): void
    {
        $success        = true;
        $images         = [['url' => 'https://example.com/image1.jpg'], ['url' => 'https://example.com/image2.jpg']];
        $error          = null;
        $metadata       = ['model' => 'dall-e-3'];
        $processingTime = 2.5;
        $provider       = 'openai';
        $jobId          = 'job-123';

        $dto = new ImageResponse($success, $images, $error, $metadata, $processingTime, $provider, $jobId);

        $this->assertSame($success, $dto->success);
        $this->assertSame($images, $dto->images);
        $this->assertSame($error, $dto->error);
        $this->assertSame($metadata, $dto->metadata);
        $this->assertSame($processingTime, $dto->processingTime);
        $this->assertSame($provider, $dto->provider);
        $this->assertSame($jobId, $dto->jobId);
    }

    public function test_constructs_successfully_with_minimal_parameters(): void
    {
        $dto = new ImageResponse(true);

        $this->assertTrue($dto->success);
        $this->assertSame([], $dto->images);
        $this->assertNull($dto->error);
        $this->assertSame([], $dto->metadata);
        $this->assertSame(0.0, $dto->processingTime);
        $this->assertSame('', $dto->provider);
        $this->assertNull($dto->jobId);
    }

    public function test_constructs_successfully_for_failure_response(): void
    {
        $success        = false;
        $error          = 'API error';
        $metadata       = ['code' => 500];
        $processingTime = 1.2;
        $provider       = 'stability-ai';

        $dto = new ImageResponse($success, [], $error, $metadata, $processingTime, $provider);

        $this->assertFalse($dto->success);
        $this->assertSame([], $dto->images);
        $this->assertSame($error, $dto->error);
        $this->assertSame($metadata, $dto->metadata);
        $this->assertSame($processingTime, $dto->processingTime);
        $this->assertSame($provider, $dto->provider);
        $this->assertNull($dto->jobId);
    }

    public function test_to_array_returns_correct_array(): void
    {
        $success        = true;
        $images         = [['url' => 'https://example.com/generated.jpg']];
        $error          = null;
        $metadata       = ['model' => 'gemini', 'tokens' => 150];
        $processingTime = 3.75;
        $provider       = 'gemini';
        $jobId          = 'job-gemini-456';

        $dto   = new ImageResponse($success, $images, $error, $metadata, $processingTime, $provider, $jobId);
        $array = $dto->toArray();

        $expected = [
            'success'         => $success,
            'images'          => $images,
            'error'           => $error,
            'metadata'        => $metadata,
            'processing_time' => round($processingTime, 3),
            'provider'        => $provider,
            'job_id'          => $jobId,
        ];

        $this->assertSame($expected, $array);
    }
}
