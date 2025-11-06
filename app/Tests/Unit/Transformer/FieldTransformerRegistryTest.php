<?php

declare(strict_types=1);

namespace App\Tests\Unit\Transformer;

use App\Contracts\Transformer\FieldTransformerInterface;
use App\Exceptions\Transformer\InvalidTransformerException;
use App\Services\Transformer\FieldTransformerRegistry;
use App\Services\Transformer\FieldTransformers\PriceTransformer;
use Illuminate\Contracts\Container\Container;
use Mockery;
use Tests\UnitTestBase;

/**
 * Test suite for FieldTransformerRegistry.
 */
class FieldTransformerRegistryTest extends UnitTestBase
{
    private FieldTransformerRegistry $registry;
    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = Mockery::mock(Container::class);
        $this->registry  = new FieldTransformerRegistry($this->container);
    }

    public function test_registers_valid_transformer(): void
    {
        $this->registry->register('price', PriceTransformer::class);

        $this->assertTrue($this->registry->has('price'));
    }

    public function test_throws_exception_for_invalid_transformer(): void
    {
        $this->expectException(InvalidTransformerException::class);

        $this->registry->register('price', \stdClass::class);
    }

    public function test_resolves_registered_transformer(): void
    {
        $mockTransformer = Mockery::mock(FieldTransformerInterface::class);

        $this->container
            ->shouldReceive('make')
            ->with(PriceTransformer::class)
            ->andReturn($mockTransformer);

        $this->registry->register('price', PriceTransformer::class);
        $resolved = $this->registry->resolve('price');

        $this->assertInstanceOf(FieldTransformerInterface::class, $resolved);
    }

    public function test_returns_null_for_unregistered_field(): void
    {
        $this->assertNull($this->registry->resolve('nonexistent'));
        $this->assertFalse($this->registry->has('nonexistent'));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
