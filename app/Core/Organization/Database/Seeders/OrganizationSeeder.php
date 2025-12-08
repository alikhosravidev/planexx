<?php

declare(strict_types=1);

namespace App\Core\Organization\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(
            [
                UserSeeder::class,
                DepartmentSeeder::class,
            ]
        );
    }
}
