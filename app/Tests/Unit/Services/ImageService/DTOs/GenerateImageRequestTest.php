<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services\ImageService\DTOs;

use App\Services\AIImageService\DTOs\GenerateImageRequest;
use InvalidArgumentException;
use Tests\PureUnitTestBase;

class GenerateImageRequestTest extends PureUnitTestBase
{
    public function test_constructs_successfully_with_all_parameters(): void
    {
        $prompt         = 'A beautiful sunset';
        $negativePrompt = 'no people';
        $memberProfile  = 'user123';
        $numberOfImages = 2;
        $size           = '512x512';
        $style          = 'vivid';
        $colors         = ['red', 'orange'];
        $metadata       = ['session_id' => 'abc123'];

        $dto = new GenerateImageRequest($prompt, $negativePrompt, $memberProfile, $numberOfImages, $size, $style, $colors, $metadata);

        $this->assertSame(trim($prompt), $dto->prompt);
        $this->assertSame($negativePrompt, $dto->negativePrompt);
        $this->assertSame($memberProfile, $dto->memberProfile);
        $this->assertSame($numberOfImages, $dto->numberOfImages);
        $this->assertSame($size, $dto->size);
        $this->assertSame($style, $dto->style);
        $this->assertSame($colors, $dto->colors);
        $this->assertSame($metadata, $dto->metadata);
    }

    public function test_constructs_successfully_with_minimal_parameters(): void
    {
        $prompt = 'A cat';

        $dto = new GenerateImageRequest($prompt);

        $this->assertSame(trim($prompt), $dto->prompt);
        $this->assertNull($dto->negativePrompt);
        $this->assertNull($dto->memberProfile);
        $this->assertSame(1, $dto->numberOfImages);
        $this->assertSame('1024x1024', $dto->size);
        $this->assertSame('natural', $dto->style);
        $this->assertSame([], $dto->colors);
        $this->assertSame([], $dto->metadata);
    }

    public function test_throws_exception_for_empty_prompt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Prompt cannot be empty');

        new GenerateImageRequest('');
    }

    public function test_throws_exception_for_whitespace_only_prompt(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Prompt cannot be empty');

        new GenerateImageRequest('   ');
    }

    public function test_throws_exception_for_invalid_number_of_images(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Number of images must be between 1 and 4');

        new GenerateImageRequest('test prompt', null, null, 5);
    }

    public function test_throws_exception_for_invalid_size_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid size format. Use format: WIDTHxHEIGHT');

        new GenerateImageRequest('test prompt', null, null, 1, 'invalid-size');
    }

    public function test_to_array_returns_correct_array(): void
    {
        $prompt         = 'A mountain landscape';
        $negativePrompt = 'no trees';
        $memberProfile  = 'user456';
        $numberOfImages = 3;
        $size           = '768x768';
        $style          = 'photographic';
        $colors         = ['blue', 'green'];
        $metadata       = ['request_id' => 'xyz789'];

        $dto   = new GenerateImageRequest($prompt, $negativePrompt, $memberProfile, $numberOfImages, $size, $style, $colors, $metadata);
        $array = $dto->toArray();

        $expected = [
            'prompt'           => trim($prompt),
            'negative_prompt'  => $negativePrompt,
            'number_of_images' => $numberOfImages,
            'size'             => $size,
            'style'            => $style,
            'colors'           => $colors,
            'member_profile'   => $memberProfile,
            'metadata'         => $metadata,
        ];

        $this->assertSame($expected, $array);
    }
}
