<?php

declare(strict_types=1);

namespace App\Tests\Integration\Transformer;

use App\Contracts\Model\BaseModel;
use App\Contracts\Transformer\BaseTransformer;
use App\Services\Transformer\TransformerFactory;
use Tests\IntegrationTestBase;

/**
 * Integration test for the transformer system.
 */
class BaseTransformerTest extends IntegrationTestBase
{
    private TransformerFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = app(TransformerFactory::class);
    }

    public function test_transforms_model_with_all_features(): void
    {
        $user        = new TestModel();
        $transformer = resolve(TestTransformer::class);
        $result      = $transformer->transformModel($user);

        // Assertions
        $this->assertArrayHasKey('first_name', $result);
        $this->assertArrayHasKey('last_name', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('full_name', $result);
        $this->assertArrayHasKey('display_name', $result);
        $this->assertArrayNotHasKey('password', $result);

        $this->assertEquals('John', $result['first_name']);
        $this->assertEquals('Doe', $result['last_name']);
        $this->assertEquals('john@example.com', $result['email']);
        $this->assertEquals('John Doe', $result['full_name']);
        $this->assertEquals('JOHN DOE', $result['display_name']);
    }

    public function test_pipeline_processes_steps_correctly(): void
    {
        $profile     = new ProfileModel();
        $transformer = resolve(ProfileTransformer::class);
        $result      = $transformer->transformModel($profile);

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('computed_field', $result);
        $this->assertArrayHasKey('virtual_field', $result);
        $this->assertArrayNotHasKey('secret_field', $result);

        $this->assertEquals('John', $result['name']);
        $this->assertEquals('computed_John', $result['computed_field']);
        $this->assertEquals('virtual_John', $result['virtual_field']);
    }
}

class TestModel extends BaseModel
{
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'created_at'];
    protected $appends  = ['full_name'];

    public function __construct()
    {
        parent::__construct(
            [
                'first_name' => 'John',
                'last_name'  => 'Doe',
                'email'      => 'john@example.com',
                'password'   => 'secret123',
                'created_at' => now(),
            ]
        );
    }

    public function getAppends(): array
    {
        return ['full_name'];
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

class TestTransformer extends BaseTransformer
{
    protected function getVirtualFieldResolvers(): array
    {
        return [
            'display_name' => fn ($model) => strtoupper($model->first_name . ' ' . $model->last_name),
        ];
    }
}

class ProfileModel extends BaseModel
{
    protected $fillable = ['name', 'secret_field'];
    protected $appends  = ['computed_field'];

    public function __construct()
    {
        parent::__construct([
            'name'         => 'John',
            'secret_field' => 'hidden',
        ]);
    }

    public function getAppends(): array
    {
        return ['computed_field'];
    }

    public function getComputedFieldAttribute(): string
    {
        return 'computed_' . $this->name;
    }
}

class ProfileTransformer extends BaseTransformer
{
    protected function getVirtualFieldResolvers(): array
    {
        return [
            'virtual_field' => fn ($model) => 'virtual_' . $model->name,
        ];
    }
}
