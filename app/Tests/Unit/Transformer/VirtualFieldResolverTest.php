<?php

declare(strict_types=1);

namespace App\Tests\Unit\Transformer;

use App\Exceptions\Transformer\VirtualFieldNotFoundException;
use App\Services\Transformer\VirtualFieldResolver;
use Tests\UnitTestBase;

/**
 * Test suite for VirtualFieldResolver.
 */
class VirtualFieldResolverTest extends UnitTestBase
{
    private VirtualFieldResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = new VirtualFieldResolver([
            'full_name' => fn ($model) => $model->first_name . ' ' . $model->last_name,
            'age'       => fn ($model) => 25,
        ]);
    }

    public function test_resolves_registered_field(): void
    {
        $model = (object) ['first_name' => 'John', 'last_name' => 'Doe'];

        $this->assertEquals('John Doe', $this->resolver->resolve('full_name', $model));
        $this->assertEquals(25, $this->resolver->resolve('age', $model));
    }

    public function test_throws_exception_for_unknown_field(): void
    {
        $this->expectException(VirtualFieldNotFoundException::class);

        $this->resolver->resolve('unknown_field', new \stdClass());
    }

    public function test_returns_available_fields(): void
    {
        $fields = $this->resolver->getAvailableFields();

        $this->assertContains('full_name', $fields);
        $this->assertContains('age', $fields);
        $this->assertCount(2, $fields);
    }
}
