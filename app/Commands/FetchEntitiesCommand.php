<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\MetadataMappers\MapEntityMetadata;
use Illuminate\Console\Command;

class FetchEntitiesCommand extends Command
{
    protected $signature = 'fetch:entitiesMap';

    protected $description = 'Register or update entities map into database';

    public function handle()
    {
        $this->info(' ');
        $this->info('Starting ...');

        MapEntityMetadata::getInstance()->reset();

        $this->info('DONE!');
    }
}
