<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Feature\V1\Admin;

use App\Core\Organization\Entities\JobPosition;
use Tests\APITestBase;

class IndexJobPositionsTest extends APITestBase
{
    private function getRoute(): string
    {
        return route('api.v1.admin.org.job-positions.index');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin();
    }

    public function test_it_lists_job_positions_successfully(): void
    {
        // Create a test job position
        JobPosition::factory()->create(['title' => 'Test Job']);

        $response = $this->getJson($this->getRoute());

        $this->assertSuccessAPIResponse($response);
        $response->assertJsonStructure([
            'result' => [
                '*' => [
                    'id',
                    'title',
                    'code',
                    'is_active',
                ],
            ],
            'meta' => [
                'pagination' => [],
            ],
        ]);
    }
}
