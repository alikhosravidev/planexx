<?php

declare(strict_types=1);

namespace App\Tests\Unit\Transformer;

use App\Contracts\Entity\BaseEntity;
use App\Contracts\Transformer\TransformationStepInterface;
use App\Services\Transformer\ModelTransformationContext;
use App\Services\Transformer\TransformationContext;
use App\Services\Transformer\TransformationPipeline;
use Tests\UnitTestBase;

/**
 * Test suite for TransformationPipeline.
 */
class TransformationPipelineTest extends UnitTestBase
{
    public function test_executes_steps_in_order(): void
    {
        $steps = [
            new class () implements TransformationStepInterface {
                public function process(TransformationContext $context): TransformationContext
                {
                    return $context->withData(['step1' => true] + $context->data);
                }
            },
            new class () implements TransformationStepInterface {
                public function process(TransformationContext $context): TransformationContext
                {
                    return $context->withData(['step2' => true] + $context->data);
                }
            },
        ];

        $pipeline = new TransformationPipeline($steps);
        $model    = new class () extends BaseEntity {
            public function getAppends(): array
            {
                return [];
            }
        };

        $context = new ModelTransformationContext(['initial' => true], $model);
        $result  = $pipeline->process($context);

        $this->assertArrayHasKey('initial', $result->data);
        $this->assertArrayHasKey('step1', $result->data);
        $this->assertArrayHasKey('step2', $result->data);
    }

    public function test_passes_context_between_steps(): void
    {
        $step1 = new class () implements TransformationStepInterface {
            public function process(TransformationContext $context): TransformationContext
            {
                return $context->withData(['processed' => true]);
            }
        };

        $step2 = new class () implements TransformationStepInterface {
            public function process(TransformationContext $context): TransformationContext
            {
                $data                = $context->data;
                $data['step2_added'] = $data['processed'] ?? false;

                return $context->withData($data);
            }
        };

        $pipeline = new TransformationPipeline([$step1, $step2]);
        $model    = new class () extends BaseEntity {
            public function getAppends(): array
            {
                return [];
            }
        };

        $context = new ModelTransformationContext([], $model);
        $result  = $pipeline->process($context);

        $this->assertTrue($result->data['processed']);
        $this->assertTrue($result->data['step2_added']);
    }

    public function test_handles_empty_steps(): void
    {
        $pipeline = new TransformationPipeline([]);
        $model    = new class () extends BaseEntity {
            public function getAppends(): array
            {
                return [];
            }
        };

        $context = new ModelTransformationContext(['test' => 'data'], $model);
        $result  = $pipeline->process($context);

        $this->assertEquals(['test' => 'data'], $result->data);
    }
}
