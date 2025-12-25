<?php

declare(strict_types=1);

namespace App\Core\Organization\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::unprepared(file_get_contents(__DIR__ . '/../seed_users.sql'));

        DB::unprepared(file_get_contents(__DIR__ . '/../seed_departments.sql'));
    }
}
