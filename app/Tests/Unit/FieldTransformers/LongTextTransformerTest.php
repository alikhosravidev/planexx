<?php

declare(strict_types=1);

namespace App\Tests\Unit\FieldTransformers;

use App\Services\Transformer\FieldTransformers\LongTextTransformer;
use Tests\UnitTestBase;

class LongTextTransformerTest extends UnitTestBase
{
    private LongTextTransformer $transformer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transformer = resolve(LongTextTransformer::class);
    }

    public function test_transforms_text_correctly_for_single_item()
    {
        $text = 'This is a sample long text for testing purposes.';

        $result = $this->transformer->transform($text);

        $this->assertArrayHasKey('short', $result);
        $this->assertArrayHasKey('full', $result);
        $this->assertArrayHasKey('lines', $result);
        $this->assertArrayHasKey('words', $result);
        $this->assertArrayHasKey('read_times', $result);
        $this->assertArrayHasKey('toc_menus', $result);

        $this->assertIsString($result['short']);
        $this->assertIsString($result['full']);
        $this->assertIsInt($result['lines']);
        $this->assertIsInt($result['words']);
        $this->assertIsInt($result['read_times']);
        $this->assertIsArray($result['toc_menus']);
    }

    public function test_transforms_empty_text_correctly()
    {
        $text = '';

        $result = $this->transformer->transform($text);

        $this->assertArrayHasKey('short', $result);
        $this->assertArrayHasKey('full', $result);
        $this->assertArrayHasKey('lines', $result);
        $this->assertArrayHasKey('words', $result);
        $this->assertArrayHasKey('read_times', $result);
        $this->assertArrayHasKey('toc_menus', $result);
    }
}
