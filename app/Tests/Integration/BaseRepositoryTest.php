<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Contracts\Model\BaseModel;
use App\Contracts\Repository\BaseRepository;
use App\Contracts\Repository\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Tests\IntegrationTestBase;
use Tests\UseOnMemoryDatabase;

/**
 * Integration tests for BaseRepository
 * These tests verify database interactions, query correctness, and state changes
 */
class BaseRepositoryTest extends IntegrationTestBase
{
    use UseOnMemoryDatabase;
    private TestModelRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupOnMemoryDatabase();

        Schema::create('test_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });

        $this->repository = new TestModelRepository();
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('test_models');

        $this->downOnMemoryDatabase();

        parent::tearDown();
    }

    public function test_all_returns_collection_of_all_models(): void
    {
        // Arrange
        TestModel::factory()->count(3)->create();

        // Act
        $result = $this->repository->all();

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
        $this->assertContainsOnlyInstancesOf(TestModel::class, $result);
    }

    public function test_all_returns_empty_collection_when_no_models_exist(): void
    {
        // Act
        $result = $this->repository->all();

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
    }

    public function test_find_returns_model_when_exists(): void
    {
        // Arrange
        $model = TestModel::factory()->create(['name' => 'Test Model']);

        // Act
        $result = $this->repository->find($model->id);

        // Assert
        $this->assertInstanceOf(TestModel::class, $result);
        $this->assertEquals($model->id, $result->id);
        $this->assertEquals('Test Model', $result->name);
    }

    public function test_find_returns_null_when_model_does_not_exist(): void
    {
        // Act
        $result = $this->repository->find(999);

        // Assert
        $this->assertNull($result);
    }

    public function test_first_returns_first_model_by_default_order(): void
    {
        // Arrange
        // Default ordering is by primary key ascending, so the first inserted record should be returned
        $firstModel = TestModel::factory()->create(['name' => 'A Model']);
        TestModel::factory()->create(['name' => 'Z Model']);

        // Act
        $result = $this->repository->first();

        // Assert
        $this->assertInstanceOf(TestModel::class, $result);
        $this->assertEquals($firstModel->id, $result->id);
        $this->assertEquals('A Model', $result->name);
    }

    public function test_first_returns_null_when_no_models_exist(): void
    {
        // Act
        $result = $this->repository->first();

        // Assert
        $this->assertNull($result);
    }

    public function test_find_or_fail_returns_model_when_exists(): void
    {
        // Arrange
        $model = TestModel::factory()->create(['name' => 'Test Model']);

        // Act
        $result = $this->repository->findOrFail($model->id);

        // Assert
        $this->assertInstanceOf(TestModel::class, $result);
        $this->assertEquals($model->id, $result->id);
    }

    public function test_find_or_fail_throws_exception_when_model_does_not_exist(): void
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->repository->findOrFail(999);
    }

    public function test_create_persists_new_model_to_database(): void
    {
        // Arrange
        $data = [
            'name'   => 'New Test Model',
            'email'  => 'test@example.com',
            'status' => true,
        ];

        // Act
        $result = $this->repository->create($data);

        // Assert
        $this->assertInstanceOf(TestModel::class, $result);
        $this->assertEquals('New Test Model', $result->name);
        $this->assertEquals('test@example.com', $result->email);
        $this->assertTrue($result->status);

        // Verify persistence
        $this->assertDatabaseHas('test_models', $data);
    }

    public function test_update_modifies_existing_model_and_returns_updated_model(): void
    {
        // Arrange
        $model = TestModel::factory()->create([
            'name'  => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $updateData = [
            'name'   => 'Updated Name',
            'status' => true,
        ];

        // Act
        $result = $this->repository->update($model->id, $updateData);

        // Assert
        $this->assertInstanceOf(TestModel::class, $result);
        $this->assertEquals($model->id, $result->id);
        $this->assertEquals('Updated Name', $result->name);
        $this->assertTrue($result->status);
        $this->assertEquals('original@example.com', $result->email); // unchanged

        // Verify database state
        $this->assertDatabaseHas('test_models', [
            'id'     => $model->id,
            'name'   => 'Updated Name',
            'email'  => 'original@example.com',
            'status' => true,
        ]);
    }

    public function test_update_throws_exception_when_model_does_not_exist(): void
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->repository->update(999, ['name' => 'Updated Name']);
    }

    public function test_delete_removes_model_from_database(): void
    {
        // Arrange
        $model = TestModel::factory()->create();

        // Act
        $result = $this->repository->delete($model->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('test_models', ['id' => $model->id]);
    }

    public function test_delete_returns_false_when_model_does_not_exist(): void
    {
        // Act
        $result = $this->repository->delete(999);

        // Assert
        $this->assertFalse($result);
    }

    public function test_paginate_returns_paginator_with_default_per_page(): void
    {
        // Arrange
        TestModel::factory()->count(25)->create();

        // Act
        $result = $this->repository->paginate();

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(15, $result->perPage());
        $this->assertEquals(25, $result->total());
        $this->assertCount(15, $result->items());
    }

    public function test_paginate_returns_paginator_with_custom_per_page(): void
    {
        // Arrange
        TestModel::factory()->count(10)->create();

        // Act
        $result = $this->repository->paginate(5);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(5, $result->perPage());
        $this->assertEquals(10, $result->total());
        $this->assertCount(5, $result->items());
    }

    public function test_find_by_returns_collection_matching_field_value(): void
    {
        // Arrange
        TestModel::factory()->count(3)->create(['status' => false]);
        TestModel::factory()->count(2)->create(['status' => true]);

        // Act
        $result = $this->repository->findBy('status', true);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(TestModel::class, $result);

        foreach ($result as $model) {
            $this->assertTrue($model->status);
        }
    }

    public function test_find_one_by_returns_first_model_matching_field_value(): void
    {
        // Arrange
        TestModel::factory()->create(['email' => 'first@example.com']);
        TestModel::factory()->create(['email' => 'second@example.com']);

        // Act
        $result = $this->repository->findOneBy('email', 'first@example.com');

        // Assert
        $this->assertInstanceOf(TestModel::class, $result);
        $this->assertEquals('first@example.com', $result->email);
    }

    public function test_find_one_by_returns_null_when_no_match(): void
    {
        // Arrange
        TestModel::factory()->create(['email' => 'existing@example.com']);

        // Act
        $result = $this->repository->findOneBy('email', 'nonexistent@example.com');

        // Assert
        $this->assertNull($result);
    }

    public function test_count_returns_total_number_of_models(): void
    {
        // Arrange
        TestModel::factory()->count(7)->create();

        // Act
        $result = $this->repository->count();

        // Assert
        $this->assertEquals(7, $result);
    }

    public function test_count_returns_zero_when_no_models_exist(): void
    {
        // Act
        $result = $this->repository->count();

        // Assert
        $this->assertEquals(0, $result);
    }

    public function test_push_criteria_adds_criteria_to_repository(): void
    {
        // Arrange
        $criteria = $this->createMock(CriteriaInterface::class);

        // Act
        $result = $this->repository->pushCriteria($criteria);

        // Assert
        $this->assertSame($this->repository, $result);
        // Do not assert internal state; verify behavior by applying the criteria on a simple query
        TestModel::factory()->create(['status' => false]);
        TestModel::factory()->create(['status' => true]);

        $criteria->expects($this->once())
            ->method('apply')
            ->willReturnCallback(function (Builder $query) {
                return $query->where('status', true);
            });

        $resultAll = $this->repository->all();
        $this->assertCount(1, $resultAll);
    }

    public function test_apply_criteria_applies_criteria_and_clears_collection(): void
    {
        // Arrange
        $criteria = $this->createMock(CriteriaInterface::class);
        $criteria->expects($this->once())
            ->method('apply')
            ->willReturnCallback(function (Builder $query) {
                return $query->where('status', true);
            });

        $this->repository->pushCriteria($criteria);

        // Seed data with mixed statuses
        TestModel::factory()->count(2)->create(['status' => false]);
        TestModel::factory()->count(3)->create(['status' => true]);

        // Act - ensure criteria is applied once and then cleared (next call shouldn't be filtered)
        $filtered   = $this->repository->all();
        $unfiltered = $this->repository->all();

        // Assert
        $this->assertCount(3, $filtered);
        $this->assertCount(5, $unfiltered);
    }

    public function test_criteria_are_applied_to_all_method(): void
    {
        // Arrange
        TestModel::factory()->count(3)->create(['status' => false]);
        TestModel::factory()->count(2)->create(['status' => true]);

        $criteria = $this->createMock(CriteriaInterface::class);
        $criteria->expects($this->once())
            ->method('apply')
            ->willReturnCallback(function (Builder $query) {
                return $query->where('status', true);
            });

        // Act
        $this->repository->pushCriteria($criteria);
        $result = $this->repository->all();

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);

        foreach ($result as $model) {
            $this->assertTrue($model->status);
        }
    }

    public function test_criteria_are_applied_to_find_method(): void
    {
        // Arrange
        $inactiveModel = TestModel::factory()->create(['status' => false]);
        $activeModel   = TestModel::factory()->create(['status' => true]);

        $criteria = $this->createMock(CriteriaInterface::class);
        $criteria->expects($this->once())
            ->method('apply')
            ->willReturnCallback(function (Builder $query) {
                return $query->where('status', true);
            });

        // Act
        $this->repository->pushCriteria($criteria);
        $result = $this->repository->find($inactiveModel->id);

        // Assert
        $this->assertNull($result); // Should not find inactive model due to criteria
    }

    public function test_criteria_are_applied_to_count_method(): void
    {
        // Arrange
        TestModel::factory()->count(3)->create(['status' => false]);
        TestModel::factory()->count(2)->create(['status' => true]);

        $criteria = $this->createMock(CriteriaInterface::class);
        $criteria->expects($this->once())
            ->method('apply')
            ->willReturnCallback(function (Builder $query) {
                return $query->where('status', true);
            });

        // Act
        $this->repository->pushCriteria($criteria);
        $result = $this->repository->count();

        // Assert
        $this->assertEquals(2, $result);
    }

    public function test_criteria_are_applied_to_paginate_method(): void
    {
        // Arrange
        TestModel::factory()->count(5)->create(['status' => false]);
        TestModel::factory()->count(3)->create(['status' => true]);

        $criteria = $this->createMock(CriteriaInterface::class);
        $criteria->expects($this->once())
            ->method('apply')
            ->willReturnCallback(function (Builder $query) {
                return $query->where('status', true);
            });

        // Act
        $this->repository->pushCriteria($criteria);
        $result = $this->repository->paginate(10);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(3, $result->total());
        $this->assertCount(3, $result->items());
    }

    public function test_multiple_criteria_are_applied_in_order(): void
    {
        // Arrange
        TestModel::factory()->create(['name' => 'Test A', 'status' => true, 'email' => 'a@example.com']);
        TestModel::factory()->create(['name' => 'Test B', 'status' => true, 'email' => 'b@example.com']);
        TestModel::factory()->create(['name' => 'Test C', 'status' => false, 'email' => 'c@example.com']);

        $statusCriteria = $this->createMock(CriteriaInterface::class);
        $statusCriteria->expects($this->once())
            ->method('apply')
            ->willReturnCallback(function (Builder $query) {
                return $query->where('status', true);
            });

        $nameCriteria = $this->createMock(CriteriaInterface::class);
        $nameCriteria->expects($this->once())
            ->method('apply')
            ->willReturnCallback(function (Builder $query) {
                return $query->where('name', 'like', 'Test %');
            });

        // Act
        $this->repository->pushCriteria($statusCriteria);
        $this->repository->pushCriteria($nameCriteria);
        $result = $this->repository->all();

        // Assert
        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(TestModel::class, $result);
    }

    public function test_unique_constraint_violation_throws_exception_and_persists_first_row(): void
    {
        // Arrange
        $initialCount = TestModel::count();

        // Act & Assert
        try {
            $this->repository->create(['name' => 'Test', 'email' => 'test@example.com']);
            // Duplicate email to trigger unique constraint error at DB level
            $this->repository->create(['name' => 'Test2', 'email' => 'test@example.com']);
        } catch (\Exception $e) {
            // Expected due to unique constraint
        }

        // Only the first insert should persist within the surrounding test transaction
        $this->assertEquals($initialCount + 1, TestModel::count());
    }
}

class TestModel extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'status', 'created_at', 'updated_at'];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function newFactory()
    {
        return TestModelFactory::new();
    }
}

class TestModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = TestModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'   => $this->faker->name(),
            'email'  => $this->faker->unique()->safeEmail(),
            'status' => $this->faker->boolean(),
        ];
    }
}

class TestModelRepository extends BaseRepository
{
    public function model(): string
    {
        return TestModel::class;
    }
}
