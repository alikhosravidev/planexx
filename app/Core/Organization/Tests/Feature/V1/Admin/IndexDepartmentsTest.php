<?php

declare(strict_types=1);

namespace App\Core\Organization\Tests\Feature\V1\Admin;

use App\Core\Organization\Entities\Department;
use Tests\APITestBase;

class IndexDepartmentsTest extends APITestBase
{
    private function getRoute(): string
    {
        return route(
            'organization.departments.index',
            ['includes' => 'manager']
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin();
    }

    public function test_it_lists_departments_successfully(): void
    {
        Department::factory()->create();

        $response = $this->getJson($this->getRoute());

        $response->assertOk();
        $response->assertJson([
            'status' => true,
        ]);
        $response->assertJsonStructure([
            'result' => [
                '*' => [
                    'id',
                    'name',
                    'code',
                    'is_active',
                    'manager',
                ],
            ],
            'meta' => [
                'pagination' => [],
            ],
        ]);
    }
}
