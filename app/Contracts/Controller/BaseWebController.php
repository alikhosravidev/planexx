<?php

declare(strict_types=1);

namespace App\Contracts\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;
use RuntimeException;

/**
 * BaseWebController
 *
 * This controller acts as the foundation for all Admin Panel web controllers.
 * Its primary role is to provide a secure and efficient method (`forwardToApi`)
 * to dispatch internal requests to the application's own API.
 *
 * This ensures all data access goes through the complete API stack
 * (Middleware, Auth, Validation, Transformers) maintaining true API-FIRST architecture.
 *
 * Key Benefits:
 * - API-FIRST: Admin panel acts as a first-class API client
 * - Security: All requests pass through API middleware stack (validation, authorization, rate limiting)
 * - Consistency: Admin panel receives the same transformed data as external clients
 * - Performance: No network overhead, everything happens via internal dispatch
 * - Maintainability: No code duplication between API and web layers
 */
abstract class BaseWebController
{
    /**
     * Forward a request to the internal API using route name
     *
     * This method calls the API controller directly by route name,
     * ensuring all API middleware, validation, and transformations are applied.
     *
     * @param  string  $routeName  API route name (e.g., 'user.auth', 'user.logout')
     * @param  array  $data  Request data
     * @param  string  $method  HTTP method (default: POST)
     * @return mixed Decoded JSON response from the API
     *
     */
    protected function forwardToApi(
        string $routeName,
        array $data = [],
        string $method = 'POST'
    ): mixed {
        // 1. Get the route by name
        $route = Route::getRoutes()->getByName($routeName);

        if (!$route) {
            throw new RuntimeException("API route '{$routeName}' not found");
        }

        // 2. Create a sub-request for this specific route
        $subRequest = Request::create(
            uri: $route->uri(),
            method: $method,
            parameters: $data,
            server: request()->server->all()
        );

        // 3. Set headers
        $subRequest->headers->set('Accept', 'application/json');

        // 4. Authentication forwarding
        $webUser = auth('web')->user();

        if ($webUser instanceof Authenticatable) {
            Sanctum::actingAs($webUser, abilities: ['*'], guard: 'sanctum');
        }

        // 5. Bind route to request and run
        $subRequest->setRouteResolver(fn () => $route);
        $response = $route->bind($subRequest)->run();

        // 6. Decode response
        $decodedResponse = json_decode($response->getContent(), true);

        // 7. Handle validation errors
        if ($response->getStatusCode() === 422) {
            Session::flashInput($data);

            throw ValidationException::withMessages($decodedResponse['errors'] ?? []);
        }

        return $decodedResponse;
    }

}
