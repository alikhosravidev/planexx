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

use App\Core\User\Entities\PersonalAccessToken;
use Illuminate\Testing\TestResponse;

abstract class APITestBase extends TestCase
{
    use HasActor;

    protected PersonalAccessToken $accessToken;

    /**
     * Asserts that the API response is successful.
     *
     * This method performs the following checks:
     * 1. The response status is 200.
     * 2. The 'status' key exists and is true.
     * 3. The 'result' key exists and is not empty.
     * 4. The 'errors' key does not exist or is empty.
     *
     * @param TestResponse $response the response to assert
     */
    protected function assertSuccessAPIResponse(TestResponse $response, ?string $action = null): void
    {
        // 1. Check if the response status is 200
        match ($action) {
            'accepted' => $response->assertAccepted(),
            'created'  => $response->assertCreated(),
            default    => $response->assertOk(),
        };

        // 2. Check if the 'status' key exists and is true
        $response->assertJson([
            'status' => true,
        ]);

        // 3. Check if the 'result' key exists and is not empty
        $result = $response->json('result');
        self::assertNotEmpty($result, "'result' key should not be empty");

        // 4. Check if the 'errors' key does not exist or is empty
        $errors = $response->json('errors');
        self::assertEmpty($errors, "'errors' key should not exist or should be empty");
    }
}
