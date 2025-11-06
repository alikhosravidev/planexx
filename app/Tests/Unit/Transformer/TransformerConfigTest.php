<?php

declare(strict_types=1);

namespace App\Tests\Unit\Transformer;

use App\Services\Transformer\FieldTransformers\DateTimeTransformer;
use App\Services\Transformer\FieldTransformers\PriceTransformer;
use App\Services\Transformer\TransformerConfig;
use Tests\UnitTestBase;

/**
 * Test suite for TransformerConfig.
 */
class TransformerConfigTest extends UnitTestBase
{
    public function test_creates_with_defaults(): void
    {
        $config = TransformerConfig::default();

        $this->assertNotEmpty($config->fieldTransformers);
        $this->assertContains(DateTimeTransformer::class, $config->fieldTransformers);
        $this->assertTrue($config->includeAccessors);
        $this->assertNotEmpty($config->blacklistedFields);
        $this->assertContains('password', $config->blacklistedFields);
    }

    public function test_merges_configs(): void
    {
        $base     = TransformerConfig::default();
        $override = new TransformerConfig(
            fieldTransformers: ['custom_field' => PriceTransformer::class],
            blacklistedFields: ['password'],
            availableIncludes: ['posts'],
            defaultIncludes: [],
            includeAccessors: false,
        );

        $merged = $base->merge($override);

        $this->assertArrayHasKey('custom_field', $merged->fieldTransformers);
        $this->assertContains('password', $merged->blacklistedFields);
        $this->assertContains('posts', $merged->availableIncludes);
        $this->assertFalse($merged->includeAccessors);
    }

    public function test_checks_blacklist(): void
    {
        $config = new TransformerConfig(
            fieldTransformers: [],
            blacklistedFields: ['password', 'secret'],
            availableIncludes: [],
            defaultIncludes: [],
            includeAccessors: true,
        );

        $this->assertTrue($config->isBlacklisted('password'));
        $this->assertTrue($config->isBlacklisted('secret'));
        $this->assertFalse($config->isBlacklisted('email'));
    }

    public function test_resolves_field_transformer(): void
    {
        $config = TransformerConfig::default();

        $this->assertEquals(DateTimeTransformer::class, $config->getFieldTransformer('created_at'));
        $this->assertNull($config->getFieldTransformer('nonexistent_field'));
    }
}
