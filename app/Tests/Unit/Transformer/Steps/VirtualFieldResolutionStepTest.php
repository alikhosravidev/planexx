<?php

declare(strict_types=1);

namespace App\Tests\Unit\Transformer\Steps;

use App\Contracts\Entity\BaseEntity;
use App\Contracts\Transformer\VirtualFieldResolverInterface;
use App\Services\Transformer\ModelTransformationContext;
use App\Services\Transformer\Steps\VirtualFieldResolutionStep;
use App\Services\Transformer\TransformationContext;
use Mockery;
use Psr\Log\LoggerInterface;
use Tests\UnitTestBase;

/**
 * Test suite for VirtualFieldResolutionStep.
 */
class VirtualFieldResolutionStepTest extends UnitTestBase
{
    private VirtualFieldResolutionStep $step;
    private VirtualFieldResolverInterface $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = Mockery::mock(VirtualFieldResolverInterface::class);
        $logger         = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('warning')->byDefault();
        $this->step = new VirtualFieldResolutionStep($this->resolver, $logger);
    }

    public function test_adds_virtual_fields(): void
    {
        $employee = new EmployeeEntity();

        $this->resolver
            ->shouldReceive('getAvailableFields')
            ->andReturn(['full_name', 'age']);

        $this->resolver
            ->shouldReceive('resolve')
            ->with('full_name', $employee)
            ->andReturn('John Doe');

        $this->resolver
            ->shouldReceive('resolve')
            ->with('age', $employee)
            ->andReturn(25);

        $data    = ['first_name' => 'John', 'last_name' => 'Doe'];
        $context = new ModelTransformationContext($data, $employee);
        $result  = $this->step->process($context);

        $this->assertArrayHasKey('full_name', $result->data);
        $this->assertArrayHasKey('age', $result->data);
        $this->assertEquals('John Doe', $result->data['full_name']);
        $this->assertEquals(25, $result->data['age']);
    }

    public function test_handles_resolution_errors_gracefully(): void
    {
        $contact = new ContactEntity();

        $this->resolver
            ->shouldReceive('getAvailableFields')
            ->andReturn(['full_name']);

        $this->resolver
            ->shouldReceive('resolve')
            ->with('full_name', $contact)
            ->andThrow(new \Exception('Resolution failed'));

        $data    = ['first_name' => 'John'];
        $context = new ModelTransformationContext($data, $contact);
        $result  = $this->step->process($context);

        $this->assertArrayNotHasKey('full_name', $result->data);
    }

    public function test_preserves_existing_data(): void
    {
        $this->resolver
            ->shouldReceive('getAvailableFields')
            ->andReturn([]);

        $data    = ['name' => 'John', 'email' => 'john@example.com'];
        $context = new TransformationContext($data, []);

        $result = $this->step->process($context);

        $this->assertEquals($data, $result->data);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

class EmployeeEntity extends BaseEntity
{
    protected $fillable = ['first_name', 'last_name'];

    public function __construct()
    {
        parent::__construct([
            'first_name' => 'John',
            'last_name'  => 'Doe',
        ]);
    }

    public function getAppends(): array
    {
        return [];
    }
}

class ContactEntity extends BaseEntity
{
    protected $fillable = ['first_name'];

    public function __construct()
    {
        parent::__construct(['first_name' => 'John']);
    }

    public function getAppends(): array
    {
        return [];
    }
}
