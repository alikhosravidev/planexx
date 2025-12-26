<?php

declare(strict_types=1);

namespace App\Core\FileManager\Http\Controllers\V1\Client;

use App\Contracts\Controller\BaseAPIController;
use App\Core\FileManager\Http\Transformers\V1\Client\FileTransformer;
use App\Core\FileManager\Repositories\FileRepository;

class ClientFileController extends BaseAPIController
{
    public function __construct(
        FileRepository  $repository,
        FileTransformer $transformer,
    ) {
        parent::__construct($repository, $transformer);
    }
}
