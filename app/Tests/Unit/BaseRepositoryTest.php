<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Contracts\Model\BaseModel;
use App\Contracts\Repository\BaseRepository;
use App\Contracts\Repository\CriteriaInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\UnitTestBase;

class BaseRepositoryTest extends UnitTestBase
{
    private BaseRepository $repository;
    private BaseModel $mockModel;
    private Builder $mockBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockModel   = Mockery::mock(BaseModel::class);
        $this->mockBuilder = Mockery::mock(Builder::class);

        $this->mockModel->shouldReceive('newQuery')
            ->byDefault()
            ->andReturn($this->mockBuilder);

        $this->app->instance(BaseModel::class, $this->mockModel);

        $this->repository = new class () extends BaseRepository {
            public function model(): string
            {
                return BaseModel::class;
            }

            public function setModel(BaseModel $model): void
            {
                $this->model = $model;
            }

            public function setQuery(Builder $query): void
            {
                $this->query = $query;
            }

            public function getCriteria(): Collection
            {
                return $this->criteria;
            }
        };
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_make_model_returns_model_instance_when_valid_base_model(): void
    {
        $mockModel = Mockery::mock(BaseModel::class);
        $mockModel->shouldReceive('newQuery')
            ->once()
            ->andReturn($this->mockBuilder);

        $this->app->instance(BaseModel::class, $mockModel);

        $result = $this->repository->makeModel();

        $this->assertInstanceOf(BaseModel::class, $result);
    }

    public function test_make_model_throws_exception_when_not_base_model_instance(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('must be an instance of');

        $invalidModel = Mockery::mock(\Illuminate\Database\Eloquent\Model::class);
        $this->app->instance(BaseModel::class, $invalidModel);

        $this->repository->makeModel();
    }

    public function test_reset_model_reinitializes_model_and_query(): void
    {
        $mockModel = Mockery::mock(BaseModel::class);
        $mockModel->shouldReceive('newQuery')
            ->twice()
            ->andReturn($this->mockBuilder);

        $this->app->instance(BaseModel::class, $mockModel);

        $this->repository->makeModel();
        $this->repository->resetModel();

        $this->assertTrue(true);
    }

    public function test_all_returns_collection_and_resets_model(): void
    {
        $expectedCollection = new Collection([
            (object)['id' => 1, 'name' => 'Item 1'],
            (object)['id' => 2, 'name' => 'Item 2'],
        ]);

        $this->mockBuilder->shouldReceive('get')
            ->once()
            ->andReturn($expectedCollection);

        $result = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }

    public function test_find_returns_model_when_exists(): void
    {
        $expectedId    = 5;
        $expectedModel = Mockery::mock(BaseModel::class)->shouldIgnoreMissing();
        $expectedModel->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($expectedId);
        $expectedModel->id = $expectedId;

        $this->mockBuilder->shouldReceive('find')
            ->once()
            ->with($expectedId)
            ->andReturn($expectedModel);

        $result = $this->repository->find($expectedId);

        $this->assertInstanceOf(BaseModel::class, $result);
        $this->assertEquals($expectedId, $result->id);
    }

    public function test_find_returns_null_when_not_exists(): void
    {
        $nonExistentId = 999;

        $this->mockBuilder->shouldReceive('find')
            ->once()
            ->with($nonExistentId)
            ->andReturn(null);

        $result = $this->repository->find($nonExistentId);

        $this->assertNull($result);
    }

    public function test_first_returns_first_model(): void
    {
        $expectedModel = Mockery::mock(BaseModel::class)->shouldIgnoreMissing();
        $expectedModel->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1);
        $expectedModel->id = 1;

        $this->mockBuilder->shouldReceive('first')
            ->once()
            ->andReturn($expectedModel);

        $result = $this->repository->first();

        $this->assertInstanceOf(BaseModel::class, $result);
        $this->assertEquals(1, $result->id);
    }

    public function test_first_returns_null_when_no_records(): void
    {
        $this->mockBuilder->shouldReceive('first')
            ->once()
            ->andReturn(null);

        $result = $this->repository->first();

        $this->assertNull($result);
    }

    public function test_find_or_fail_returns_model_when_exists(): void
    {
        $expectedId    = 10;
        $expectedModel = Mockery::mock(BaseModel::class)->shouldIgnoreMissing();
        $expectedModel->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($expectedId);
        $expectedModel->id = $expectedId;

        $this->mockBuilder->shouldReceive('findOrFail')
            ->once()
            ->with($expectedId)
            ->andReturn($expectedModel);

        $result = $this->repository->findOrFail($expectedId);

        $this->assertInstanceOf(BaseModel::class, $result);
        $this->assertEquals($expectedId, $result->id);
    }

    public function test_find_or_fail_throws_exception_when_not_exists(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $nonExistentId = 999;

        $this->mockBuilder->shouldReceive('findOrFail')
            ->once()
            ->with($nonExistentId)
            ->andThrow(new ModelNotFoundException());

        $this->repository->findOrFail($nonExistentId);
    }

    public function test_create_returns_created_model(): void
    {
        $inputData     = ['name' => 'Test Item', 'status' => 'active'];
        $expectedModel = Mockery::mock(BaseModel::class)->shouldIgnoreMissing();
        $expectedModel->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1);
        $expectedModel->shouldReceive('getAttribute')
            ->with('name')
            ->andReturn('Test Item');
        $expectedModel->id   = 1;
        $expectedModel->name = 'Test Item';

        $this->mockModel->shouldReceive('create')
            ->once()
            ->with($inputData)
            ->andReturn($expectedModel);

        $result = $this->repository->create($inputData);

        $this->assertInstanceOf(BaseModel::class, $result);
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Test Item', $result->name);
    }

    public function test_update_updates_and_returns_updated_model(): void
    {
        $targetId     = 5;
        $updateData   = ['name' => 'Updated Name', 'status' => 'inactive'];
        $updatedModel = Mockery::mock(BaseModel::class)->shouldIgnoreMissing();
        $updatedModel->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($targetId);
        $updatedModel->shouldReceive('getAttribute')
            ->with('name')
            ->andReturn('Updated Name');
        $updatedModel->id   = $targetId;
        $updatedModel->name = 'Updated Name';

        $mockWhereBuilder = Mockery::mock(Builder::class);

        $this->mockModel->shouldReceive('where')
            ->once()
            ->with('id', $targetId)
            ->andReturn($mockWhereBuilder);

        $mockWhereBuilder->shouldReceive('update')
            ->once()
            ->with($updateData)
            ->andReturn(1);

        $this->mockBuilder->shouldReceive('findOrFail')
            ->once()
            ->with($targetId)
            ->andReturn($updatedModel);

        $result = $this->repository->update($targetId, $updateData);

        $this->assertInstanceOf(BaseModel::class, $result);
        $this->assertEquals($targetId, $result->id);
        $this->assertEquals('Updated Name', $result->name);
    }

    public function test_delete_returns_true_when_successfully_deleted(): void
    {
        $targetId = 3;

        $this->mockModel->shouldReceive('destroy')
            ->once()
            ->with($targetId)
            ->andReturn(1);

        $result = $this->repository->delete($targetId);

        $this->assertTrue($result);
    }

    public function test_delete_returns_false_when_not_deleted(): void
    {
        $nonExistentId = 999;

        $this->mockModel->shouldReceive('destroy')
            ->once()
            ->with($nonExistentId)
            ->andReturn(0);

        $result = $this->repository->delete($nonExistentId);

        $this->assertFalse($result);
    }

    public function test_paginate_returns_paginator_with_default_per_page(): void
    {
        $defaultPerPage = 15;
        $mockPaginator  = Mockery::mock(LengthAwarePaginator::class);

        $this->mockBuilder->shouldReceive('paginate')
            ->once()
            ->with($defaultPerPage)
            ->andReturn($mockPaginator);

        $result = $this->repository->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_paginate_returns_paginator_with_custom_per_page(): void
    {
        $customPerPage = 25;
        $mockPaginator = Mockery::mock(LengthAwarePaginator::class);

        $this->mockBuilder->shouldReceive('paginate')
            ->once()
            ->with($customPerPage)
            ->andReturn($mockPaginator);

        $result = $this->repository->paginate($customPerPage);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_find_by_returns_collection_matching_field_value(): void
    {
        $fieldName          = 'status';
        $fieldValue         = 'active';
        $expectedCollection = new Collection([
            (object)['id' => 1, 'status' => 'active'],
            (object)['id' => 2, 'status' => 'active'],
        ]);

        $mockWhereBuilder = Mockery::mock(Builder::class);

        $this->mockModel->shouldReceive('where')
            ->once()
            ->with($fieldName, $fieldValue)
            ->andReturn($mockWhereBuilder);

        $mockWhereBuilder->shouldReceive('get')
            ->once()
            ->andReturn($expectedCollection);

        $result = $this->repository->findBy($fieldName, $fieldValue);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
    }

    public function test_find_one_by_returns_first_model_matching_field_value(): void
    {
        $fieldName     = 'email';
        $fieldValue    = 'test@example.com';
        $expectedModel = Mockery::mock(BaseModel::class)->shouldIgnoreMissing();
        $expectedModel->shouldReceive('getAttribute')
            ->with('email')
            ->andReturn($fieldValue);
        $expectedModel->email = $fieldValue;

        $mockWhereBuilder = Mockery::mock(Builder::class);

        $this->mockModel->shouldReceive('where')
            ->once()
            ->with($fieldName, $fieldValue)
            ->andReturn($mockWhereBuilder);

        $mockWhereBuilder->shouldReceive('first')
            ->once()
            ->andReturn($expectedModel);

        $result = $this->repository->findOneBy($fieldName, $fieldValue);

        $this->assertInstanceOf(BaseModel::class, $result);
        $this->assertEquals($fieldValue, $result->email);
    }

    public function test_find_one_by_returns_null_when_no_match(): void
    {
        $fieldName  = 'email';
        $fieldValue = 'nonexistent@example.com';

        $mockWhereBuilder = Mockery::mock(Builder::class);

        $this->mockModel->shouldReceive('where')
            ->once()
            ->with($fieldName, $fieldValue)
            ->andReturn($mockWhereBuilder);

        $mockWhereBuilder->shouldReceive('first')
            ->once()
            ->andReturn(null);

        $result = $this->repository->findOneBy($fieldName, $fieldValue);

        $this->assertNull($result);
    }

    public function test_count_returns_total_count(): void
    {
        $expectedCount = 42;

        $this->mockBuilder->shouldReceive('count')
            ->once()
            ->andReturn($expectedCount);

        $result = $this->repository->count();

        $this->assertEquals($expectedCount, $result);
    }

    public function test_push_criteria_adds_criteria_to_collection(): void
    {
        $mockCriteria = Mockery::mock(CriteriaInterface::class);

        $result = $this->repository->pushCriteria($mockCriteria);

        $this->assertInstanceOf(BaseRepository::class, $result);
        $this->assertCount(1, $this->repository->getCriteria());
    }

    public function test_push_criteria_returns_self_for_chaining(): void
    {
        $mockCriteria1 = Mockery::mock(CriteriaInterface::class);
        $mockCriteria2 = Mockery::mock(CriteriaInterface::class);

        $result = $this->repository
            ->pushCriteria($mockCriteria1)
            ->pushCriteria($mockCriteria2);

        $this->assertInstanceOf(BaseRepository::class, $result);
        $this->assertCount(2, $this->repository->getCriteria());
    }

    public function test_apply_criteria_applies_all_criteria_to_query(): void
    {
        $mockCriteria1 = Mockery::mock(CriteriaInterface::class);
        $mockCriteria2 = Mockery::mock(CriteriaInterface::class);

        $mockBuilder1 = Mockery::mock(Builder::class);
        $mockBuilder2 = Mockery::mock(Builder::class);

        $mockCriteria1->shouldReceive('apply')
            ->once()
            ->with($this->mockBuilder)
            ->andReturn($mockBuilder1);

        $mockCriteria2->shouldReceive('apply')
            ->once()
            ->with($mockBuilder1)
            ->andReturn($mockBuilder2);

        $this->repository->pushCriteria($mockCriteria1);
        $this->repository->pushCriteria($mockCriteria2);

        $result = $this->repository->applyCriteria();

        $this->assertInstanceOf(BaseRepository::class, $result);
        $this->assertCount(0, $this->repository->getCriteria());
    }

    public function test_apply_criteria_clears_criteria_after_applying(): void
    {
        $mockCriteria = Mockery::mock(CriteriaInterface::class);

        $mockCriteria->shouldReceive('apply')
            ->once()
            ->with($this->mockBuilder)
            ->andReturn($this->mockBuilder);

        $this->repository->pushCriteria($mockCriteria);

        $this->assertCount(1, $this->repository->getCriteria());

        $this->repository->applyCriteria();

        $this->assertCount(0, $this->repository->getCriteria());
    }

    public function test_apply_criteria_skips_non_criteria_interface_items(): void
    {
        $mockCriteria    = Mockery::mock(CriteriaInterface::class);
        $invalidCriteria = new \stdClass();

        $mockCriteria->shouldReceive('apply')
            ->once()
            ->with($this->mockBuilder)
            ->andReturn($this->mockBuilder);

        $this->repository->pushCriteria($mockCriteria);
        $this->repository->getCriteria()->push($invalidCriteria);

        $result = $this->repository->applyCriteria();

        $this->assertInstanceOf(BaseRepository::class, $result);
    }

    public function test_new_query_returns_builder_after_applying_criteria(): void
    {
        $mockCriteria = Mockery::mock(CriteriaInterface::class);

        $mockCriteria->shouldReceive('apply')
            ->once()
            ->with($this->mockBuilder)
            ->andReturn($this->mockBuilder);

        $this->repository->pushCriteria($mockCriteria);

        $result = $this->repository->newQuery();

        $this->assertInstanceOf(Builder::class, $result);
    }

    public function test_query_returns_builder_same_as_new_query(): void
    {
        $result = $this->repository->query();

        $this->assertInstanceOf(Builder::class, $result);
    }

    public function test_all_applies_criteria_before_fetching(): void
    {
        $mockCriteria       = Mockery::mock(CriteriaInterface::class);
        $expectedCollection = new Collection([]);

        $mockCriteria->shouldReceive('apply')
            ->once()
            ->with($this->mockBuilder)
            ->andReturn($this->mockBuilder);

        $this->mockBuilder->shouldReceive('get')
            ->once()
            ->andReturn($expectedCollection);

        $this->repository->pushCriteria($mockCriteria);

        $result = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_find_applies_criteria_before_finding(): void
    {
        $targetId      = 7;
        $mockCriteria  = Mockery::mock(CriteriaInterface::class);
        $expectedModel = Mockery::mock(BaseModel::class);

        $mockCriteria->shouldReceive('apply')
            ->once()
            ->with($this->mockBuilder)
            ->andReturn($this->mockBuilder);

        $this->mockBuilder->shouldReceive('find')
            ->once()
            ->with($targetId)
            ->andReturn($expectedModel);

        $this->repository->pushCriteria($mockCriteria);

        $result = $this->repository->find($targetId);

        $this->assertInstanceOf(BaseModel::class, $result);
    }

    public function test_paginate_applies_criteria_before_paginating(): void
    {
        $perPage       = 20;
        $mockCriteria  = Mockery::mock(CriteriaInterface::class);
        $mockPaginator = Mockery::mock(LengthAwarePaginator::class);

        $mockCriteria->shouldReceive('apply')
            ->once()
            ->with($this->mockBuilder)
            ->andReturn($this->mockBuilder);

        $this->mockBuilder->shouldReceive('paginate')
            ->once()
            ->with($perPage)
            ->andReturn($mockPaginator);

        $this->repository->pushCriteria($mockCriteria);

        $result = $this->repository->paginate($perPage);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_count_applies_criteria_before_counting(): void
    {
        $expectedCount = 15;
        $mockCriteria  = Mockery::mock(CriteriaInterface::class);

        $mockCriteria->shouldReceive('apply')
            ->once()
            ->with($this->mockBuilder)
            ->andReturn($this->mockBuilder);

        $this->mockBuilder->shouldReceive('count')
            ->once()
            ->andReturn($expectedCount);

        $this->repository->pushCriteria($mockCriteria);

        $result = $this->repository->count();

        $this->assertEquals($expectedCount, $result);
    }
}
