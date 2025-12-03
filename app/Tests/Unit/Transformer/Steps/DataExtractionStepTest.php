<?php

declare(strict_types=1);

namespace App\Tests\Unit\Transformer\Steps;

use App\Contracts\Entity\BaseEntity;
use App\Contracts\Transformer\DataExtractorInterface;
use App\Services\Transformer\ModelTransformationContext;
use App\Services\Transformer\Steps\DataExtractionStep;
use App\Services\Transformer\TransformationContext;
use Mockery;
use Tests\UnitTestBase;

/**
 * Test suite for DataExtractionStep.
 */
class DataExtractionStepTest extends UnitTestBase
{
    private DataExtractionStep $step;
    private DataExtractorInterface $extractor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extractor = Mockery::mock(DataExtractorInterface::class);
        $this->step      = new DataExtractionStep($this->extractor);
    }

    public function test_extracts_data_from_model(): void
    {
        $model = new class () extends BaseEntity {
            protected $fillable = ['name', 'email'];
            public function getAppends(): array
            {
                return [];
            }
        };

        $expectedData = ['name' => 'John', 'email' => 'john@example.com'];

        $this->extractor
            ->shouldReceive('extract')
            ->with($model)
            ->andReturn($expectedData);

        $context = new ModelTransformationContext([], $model);
        $result  = $this->step->process($context);

        $this->assertEquals($expectedData, $result->data);
    }

    public function test_returns_array_data_directly(): void
    {
        $data  = ['name' => 'John'];
        $model = $data; // array model

        $context = new TransformationContext($data, $model);
        $result  = $this->step->process($context);

        $this->assertEquals($data, $result->data);
        $this->extractor->shouldNotHaveReceived('extract');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
