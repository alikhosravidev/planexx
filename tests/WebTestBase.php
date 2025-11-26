<?php

declare(strict_types=1);

/*
 * This file is part of the LSP API and Panels projects
 *
 * Copyright (c) >= 2023 LSP
 *
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 * Please follow OOP, SOLID and linux philosophy in development and becarefull about anti-patterns
 *
 * @CTO Mehrdad Dadkhah <dadkhah.ir@gmail.com>
 */

namespace Tests;

use App\Core\Organization\Entities\PersonalAccessToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class WebTestBase extends TestCase implements WebTestInterface
{
    use DatabaseTransactions;
    use HasActor;

    protected PersonalAccessToken $accessToken;
}
