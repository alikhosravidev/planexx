<?php

declare(strict_types=1);

namespace App\Core\User\Tests\Unit\DTOs;

use App\Services\AIImageService\DTOs\EditImageRequest;
use InvalidArgumentException;
use Tests\PureUnitTestBase;

class EditImageRequestTest extends PureUnitTestBase
{
    public function test_constructs_successfully_with_all_parameters(): void
    {
        $imageUrl   = 'https://example.com/image.jpg';
        $operation  = 'inpaint';
        $prompt     = 'Enhance the image';
        $maskUrl    = 'https://example.com/mask.png';
        $parameters = ['strength' => 0.8];
        $metadata   = ['user_id' => 123];

        $dto = new EditImageRequest($imageUrl, $operation, $prompt, $maskUrl, $parameters, $metadata);

        $this->assertSame(trim($imageUrl), $dto->imageUrl);
        $this->assertSame(strtolower($operation), $dto->operation);
        $this->assertSame($prompt, $dto->prompt);
        $this->assertSame($maskUrl, $dto->maskUrl);
        $this->assertSame($parameters, $dto->parameters);
        $this->assertSame($metadata, $dto->metadata);
    }

    public function test_constructs_successfully_with_minimal_parameters(): void
    {
        $imageUrl  = 'https://example.com/image.jpg';
        $operation = 'variation';

        $dto = new EditImageRequest($imageUrl, $operation);

        $this->assertSame(trim($imageUrl), $dto->imageUrl);
        $this->assertSame(strtolower($operation), $dto->operation);
        $this->assertNull($dto->prompt);
        $this->assertNull($dto->maskUrl);
        $this->assertSame([], $dto->parameters);
        $this->assertSame([], $dto->metadata);
    }

    public function test_throws_exception_for_invalid_image_url(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid image URL or path');

        new EditImageRequest('invalid-url', 'variation');
    }

    public function test_throws_exception_for_invalid_operation(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid operation: invalid_op');

        new EditImageRequest('https://example.com/image.jpg', 'invalid_op');
    }

    public function test_throws_exception_for_inpaint_without_mask(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Mask URL is required for inpaint operation');

        new EditImageRequest('https://example.com/image.jpg', 'inpaint');
    }

    public function test_to_array_returns_correct_array(): void
    {
        $imageUrl   = 'https://example.com/image.jpg';
        $operation  = 'variation';
        $prompt     = 'Make it brighter';
        $maskUrl    = 'https://example.com/mask.png';
        $parameters = ['brightness' => 1.2];
        $metadata   = ['session' => 'abc'];

        $dto   = new EditImageRequest($imageUrl, $operation, $prompt, $maskUrl, $parameters, $metadata);
        $array = $dto->toArray();

        $expected = [
            'image_url'  => trim($imageUrl),
            'operation'  => strtolower($operation),
            'prompt'     => $prompt,
            'mask_url'   => $maskUrl,
            'parameters' => $parameters,
            'metadata'   => $metadata,
        ];

        $this->assertSame($expected, $array);
    }
}
