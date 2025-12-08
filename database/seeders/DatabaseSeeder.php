<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Organization\Database\Seeders\OrganizationSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Artisan::call('optimize:clear');

        Artisan::call('fetch:entitiesMap');
        Artisan::call('fetch:enums');
        Artisan::call('fetch:events');

        $this->call(
            [
                OrganizationSeeder::class,
            ]
        );
    }
}
