<?php

declare(strict_types=1);

namespace App\Core\Organization\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::unprepared(file_get_contents(__DIR__ . '/../seed_users.sql'));
    }
}
