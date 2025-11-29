<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Feature\V1\Admin;

use App\Core\Organization\Entities\JobPosition;
use App\Core\Organization\Enums\TierEnum;
use Tests\APITestBase;

class StoreJobPositionTest extends APITestBase
{
    private function getRoute(): string
    {
        return route('api.v1.admin.org.job-positions.store');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin();
    }

    public function test_it_creates_job_position_successfully(): void
    {
        $title       = 'Software Engineer';
        $code        = 'SE-' . uniqid();
        $description = 'Develops software applications';

        $data = [
            'title'       => $title,
            'code'        => $code,
            'tier'        => TierEnum::INVESTOR,
            'description' => $description,
            'is_active'   => true,
        ];

        $response = $this->postJson($this->getRoute(), $data);

        $this->assertSuccessAPIResponse($response, 'created');
        $response->assertJson([
            'status' => true,
            'result' => [
                'title'     => $title,
                'code'      => $code,
                'is_active' => true,
            ],
        ]);

        $this->assertDatabaseHas(JobPosition::class, [
            'title'       => $title,
            'code'        => $code,
            'description' => $description,
            'is_active'   => true,
        ]);
    }
}
