<?php

declare(strict_types=1);

namespace App\Tests\Unit\Transformer\Steps;

use App\Contracts\Transformer\FieldTransformerInterface;
use App\Services\Transformer\FieldTransformerRegistry;
use App\Services\Transformer\FieldTransformers\PriceTransformer;
use App\Services\Transformer\Steps\FieldTransformationStep;
use App\Services\Transformer\TransformationContext;
use Illuminate\Contracts\Container\Container;
use Mockery;
use Tests\UnitTestBase;

/**
 * Test suite for FieldTransformationStep.
 */
class FieldTransformationStepTest extends UnitTestBase
{
    private FieldTransformationStep $step;
    private FieldTransformerRegistry $registry;

    protected function setUp(): void
    {
        parent::setUp();
        $container      = Mockery::mock(Container::class);
        $this->registry = new FieldTransformerRegistry($container);
        $this->step     = new FieldTransformationStep($this->registry);
    }

    public function test_transforms_registered_fields(): void
    {
        $transformer = Mockery::mock(FieldTransformerInterface::class);
        $transformer->shouldReceive('transform')->with('raw_price')->andReturn('formatted_price');

        $container = Mockery::mock(Container::class);
        $container->shouldReceive('make')->andReturn($transformer);

        $registry = new FieldTransformerRegistry($container);
        $registry->register('price', PriceTransformer::class);

        $step = new FieldTransformationStep($registry);

        $data    = ['price' => 'raw_price', 'name' => 'John'];
        $context = new TransformationContext($data, []);

        $result = $step->process($context);

        $this->assertEquals('formatted_price', $result->data['price']);
        $this->assertEquals('John', $result->data['name']);
    }

    public function test_skips_null_values(): void
    {
        $data    = ['price' => null, 'name' => 'John'];
        $context = new TransformationContext($data, []);

        $result = $this->step->process($context);

        $this->assertNull($result->data['price']);
        $this->assertEquals('John', $result->data['name']);
    }

    public function test_preserves_unregistered_fields(): void
    {
        $data    = ['name' => 'John', 'email' => 'john@example.com'];
        $context = new TransformationContext($data, []);

        $result = $this->step->process($context);

        $this->assertEquals($data, $result->data);
    }

    public function test_transforms_nested_collection_recursively(): void
    {
        $transformer = Mockery::mock(FieldTransformerInterface::class);
        $transformer->shouldReceive('transform')->with('raw_price')->andReturn('formatted_price');

        $container = Mockery::mock(Container::class);
        $container->shouldReceive('make')->andReturn($transformer);

        $registry = new FieldTransformerRegistry($container);
        $registry->register('price', PriceTransformer::class);

        $step = new FieldTransformationStep($registry);

        $data = [
            'name'     => 'Parent',
            'price'    => 'raw_price',
            'children' => [
                [
                    'name'     => 'Child 1',
                    'price'    => 'raw_price',
                    'children' => [
                        [
                            'name'  => 'Grandchild',
                            'price' => 'raw_price',
                        ],
                    ],
                ],
                [
                    'name'  => 'Child 2',
                    'price' => 'raw_price',
                ],
            ],
        ];

        $context = new TransformationContext($data, []);
        $result  = $step->process($context);

        $this->assertEquals('formatted_price', $result->data['price']);
        $this->assertEquals('formatted_price', $result->data['children'][0]['price']);
        $this->assertEquals('formatted_price', $result->data['children'][0]['children'][0]['price']);
        $this->assertEquals('formatted_price', $result->data['children'][1]['price']);
    }

    public function test_transforms_nested_associative_array_recursively(): void
    {
        $transformer = Mockery::mock(FieldTransformerInterface::class);
        $transformer->shouldReceive('transform')->with('raw_price')->andReturn('formatted_price');

        $container = Mockery::mock(Container::class);
        $container->shouldReceive('make')->andReturn($transformer);

        $registry = new FieldTransformerRegistry($container);
        $registry->register('price', PriceTransformer::class);

        $step = new FieldTransformationStep($registry);

        $data = [
            'name'   => 'Item',
            'price'  => 'raw_price',
            'parent' => [
                'name'  => 'Parent Item',
                'price' => 'raw_price',
            ],
        ];

        $context = new TransformationContext($data, []);
        $result  = $step->process($context);

        $this->assertEquals('formatted_price', $result->data['price']);
        $this->assertEquals('formatted_price', $result->data['parent']['price']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
