<?php

declare(strict_types=1);

namespace App\Core\BPMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BPMSSeeder extends Seeder
{
    public function run(): void
    {
        DB::unprepared(file_get_contents(__DIR__ . '/../seed_bpms.sql'));
    }
}
