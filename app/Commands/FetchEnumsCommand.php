<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\MetadataMappers\MapEnumsMetadata;
use Illuminate\Console\Command;

class FetchEnumsCommand extends Command
{
    protected $signature = 'fetch:enums';

    protected $description = 'fetch enums from source code';

    public function handle()
    {
        $this->info(' ');
        $this->info('Starting ...');

        MapEnumsMetadata::getInstance()->reset();

        $this->info('DONE!');
    }
}
