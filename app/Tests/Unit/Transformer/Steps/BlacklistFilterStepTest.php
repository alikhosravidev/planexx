<?php

declare(strict_types=1);

namespace App\Tests\Unit\Transformer\Steps;

use App\Services\Transformer\Steps\BlacklistFilterStep;
use App\Services\Transformer\TransformationContext;
use App\Services\Transformer\TransformerConfig;
use Tests\UnitTestBase;

/**
 * Test suite for BlacklistFilterStep.
 */
class BlacklistFilterStepTest extends UnitTestBase
{
    private BlacklistFilterStep $step;

    protected function setUp(): void
    {
        parent::setUp();
        $config = new TransformerConfig(
            fieldTransformers: [],
            blacklistedFields: ['password', 'secret'],
            availableIncludes: [],
            defaultIncludes: [],
            includeAccessors: true,
        );
        $this->step = new BlacklistFilterStep($config);
    }

    public function test_filters_blacklisted_fields(): void
    {
        $data = [
            'name'     => 'John',
            'email'    => 'john@example.com',
            'password' => 'secret123',
            'secret'   => 'hidden',
        ];

        $context = new TransformationContext($data, []);
        $result  = $this->step->process($context);

        $this->assertArrayHasKey('name', $result->data);
        $this->assertArrayHasKey('email', $result->data);
        $this->assertArrayNotHasKey('password', $result->data);
        $this->assertArrayNotHasKey('secret', $result->data);
    }

    public function test_preserves_non_blacklisted_fields(): void
    {
        $data = [
            'name'  => 'John',
            'email' => 'john@example.com',
            'age'   => 25,
        ];

        $context = new TransformationContext($data, []);
        $result  = $this->step->process($context);

        $this->assertEquals($data, $result->data);
    }
}
