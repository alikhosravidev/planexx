<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Feature\V1\Admin;

use App\Core\Organization\Entities\Department;
use App\Core\Organization\Enums\DepartmentTypeEnum;
use Tests\APITestBase;

class StoreDepartmentTest extends APITestBase
{
    private function getRoute(): string
    {
        return route('api.v1.admin.org.departments.store');
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
        $description = 'Information technology department';

        $data = [
            'name'        => $name,
            'code'        => $code,
            'description' => $description,
            'type'        => DepartmentTypeEnum::DEPARTMENT->value,
            'icon'        => 'computer',
            'color'       => '#0000FF',
            'is_active'   => true,
        ];

        $response = $this->postJson($this->getRoute(), $data);

        $this->assertSuccessAPIResponse($response, 'created');
        $response->assertJson([
            'status' => true,
            'result' => [
                'name'        => $name,
                'code'        => $code,
                'description' => [
                    'full'       => $description,
                    'short'      => $description,
                    'lines'      => 1,
                    'words'      => 3,
                    'read_times' => 1,
                ],
                'is_active' => true,
            ],
        ]);

        $this->assertDatabaseHas(Department::class, [
            'name'        => $name,
            'code'        => $code,
            'description' => $description,
            'type'        => DepartmentTypeEnum::DEPARTMENT->value,
            'icon'        => 'computer',
            'color'       => '#0000FF',
            'is_active'   => true,
        ]);
    }
}
