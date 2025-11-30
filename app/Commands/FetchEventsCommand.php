<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\MetadataMappers\MapEventsMetadata;
use Illuminate\Console\Command;

class FetchEventsCommand extends Command
{
    protected $signature = 'fetch:events';

    protected $description = 'Register or update events into database';


    public function handle(): void
    {
        $this->info(' ');
        $this->info('Starting ...');

        MapEventsMetadata::getInstance()->reset();

        $this->info('DONE!');
    }
}
