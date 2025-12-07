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

    public function test_filters_blacklisted_fields_in_nested_collection(): void
    {
        $data = [
            'name'     => 'Parent',
            'password' => 'secret123',
            'children' => [
                [
                    'name'     => 'Child 1',
                    'password' => 'child_secret',
                    'children' => [
                        [
                            'name'     => 'Grandchild',
                            'password' => 'grandchild_secret',
                        ],
                    ],
                ],
                [
                    'name'     => 'Child 2',
                    'password' => 'child2_secret',
                ],
            ],
        ];

        $context = new TransformationContext($data, []);
        $result  = $this->step->process($context);

        $this->assertArrayNotHasKey('password', $result->data);
        $this->assertArrayNotHasKey('password', $result->data['children'][0]);
        $this->assertArrayNotHasKey('password', $result->data['children'][0]['children'][0]);
        $this->assertArrayNotHasKey('password', $result->data['children'][1]);

        $this->assertEquals('Parent', $result->data['name']);
        $this->assertEquals('Child 1', $result->data['children'][0]['name']);
        $this->assertEquals('Grandchild', $result->data['children'][0]['children'][0]['name']);
    }

    public function test_filters_blacklisted_fields_in_nested_associative_array(): void
    {
        $data = [
            'name'     => 'Item',
            'password' => 'secret123',
            'parent'   => [
                'name'     => 'Parent Item',
                'password' => 'parent_secret',
            ],
        ];

        $context = new TransformationContext($data, []);
        $result  = $this->step->process($context);

        $this->assertArrayNotHasKey('password', $result->data);
        $this->assertArrayNotHasKey('password', $result->data['parent']);

        $this->assertEquals('Item', $result->data['name']);
        $this->assertEquals('Parent Item', $result->data['parent']['name']);
    }
}
