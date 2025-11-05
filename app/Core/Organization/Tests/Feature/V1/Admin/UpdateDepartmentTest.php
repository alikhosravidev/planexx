<?php

declare(strict_types=1);

namespace Tests\Feature\Organization\V1\Admin;

use App\Core\Organization\Entities\Department;
use Tests\APITestBase;

class UpdateDepartmentTest extends APITestBase
{
    private Department $department;

    private function getRoute(): string
    {
        return route('organization.departments.update', ['department' => $this->department->id]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin();
        $this->department = Department::factory()->create(['name' => 'Old Name']);
    }

    public function test_it_updates_department_successfully(): void
    {
        $newName = 'Updated Department';

        $data = [
            'name'      => $newName,
            'is_active' => true,
        ];

        $response = $this->putJson($this->getRoute(), $data);

        $this->assertSuccessAPIResponse($response);
        $response->assertJson([
            'status' => true,
            'result' => [
                'data' => [
                    'name'      => $newName,
                    'is_active' => true,
                ],
            ],
        ]);

        $this->department->refresh();
        $this->assertEquals($newName, $this->department->name);
    }
}
