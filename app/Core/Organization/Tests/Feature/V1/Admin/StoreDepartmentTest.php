<?php

declare(strict_types=1);

namespace Tests\Feature\Organization\V1\Admin;

use App\Core\Organization\Entities\Department;
use Tests\APITestBase;

class StoreDepartmentTest extends APITestBase
{
    private function getRoute(): string
    {
        return route('organization.departments.store');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin();
    }

    public function test_it_creates_department_successfully(): void
    {
        $name        = 'IT Department';
        $code        = 'IT-' . uniqid();
        $description = 'Information Technology Department';

        $data = [
            'name'        => $name,
            'code'        => $code,
            'description' => $description,
            'is_active'   => true,
        ];

        $response = $this->postJson($this->getRoute(), $data);

        $this->assertSuccessAPIResponse($response, 'created');
        $response->assertJson([
            'status' => true,
            'result' => [
                'data' => [
                    'name'        => $name,
                    'code'        => $code,
                    'description' => $description,
                    'is_active'   => true,
                ],
            ],
        ]);

        $this->assertDatabaseHas(Department::class, [
            'name'        => $name,
            'code'        => $code,
            'description' => $description,
            'is_active'   => true,
        ]);
    }
}
