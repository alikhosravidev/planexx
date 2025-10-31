<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class IntegrationTestBase extends TestCase
{
    use DatabaseTransactions;
    use HasActor;
}
