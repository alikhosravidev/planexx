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
     * API prefix for route resolution
     * Override in child classes if needed
     */
    protected string $apiPrefix = '';

    /**
     * Forward a request to the internal API using route name
     *
     * This method calls the API controller directly by route name,
     * ensuring all API middleware, validation, and transformations are applied.
     *
     * @param  string  $routeName  API route name (e.g., 'api.v1.admin.user.auth', 'api.v1.admin.user.logout')
     * @param  array  $data  Request data
     * @param  string  $method  HTTP method (default: POST)
     * @param  array  $headers  Additional headers to send with the request
     * @return mixed Decoded JSON response from the API
     *
     */
    protected function forwardToApi(
        string $routeName,
        array $data = [],
        string $method = 'POST',
        array $headers = []
    ): mixed {
        // 1. Get the route by name
        $route = Route::getRoutes()->getByName($routeName);

        if (!$route) {
            throw new RuntimeException("API route '{$routeName}' not found");
        }

        // 2. Build URI with substituted route parameters and proper query string
        $paramNames  = $route->parameterNames();
        $routeParams = array_intersect_key($data, array_flip($paramNames));
        $payload     = array_diff_key($data, $routeParams);

        // Generate a relative URL with all parameters; extra params become query string
        $generatedUrl = app('url')->route($routeName, $routeParams + $payload, false);

        // Ensure we pass a path (with query) to Request::create
        $uri         = parse_url($generatedUrl, PHP_URL_PATH) ?: $generatedUrl;
        $queryString = parse_url($generatedUrl, PHP_URL_QUERY);
        if ($queryString) {
            $uri .= '?' . $queryString;
        }

        // Create a sub-request for this specific route
        $subRequest = Request::create(
            uri: $uri,
            method: $method,
            parameters: in_array($method, ['GET', 'HEAD'], true) ? [] : $payload,
            server: request()->server->all()
        );

        // 3. Set headers
        $subRequest->headers->set('Accept', 'application/json');

        // Add custom headers
        foreach ($headers as $key => $value) {
            $subRequest->headers->set($key, $value);
        }

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

    /**
     * Helper: GET request to API
     *
     * @param  string  $routeName  API route name
     * @param  array  $data  Query parameters
     * @param  array  $headers  Additional headers
     * @return mixed
     */
    protected function apiGet(string $routeName, array $data = [], array $headers = []): mixed
    {
        return $this->forwardToApi($routeName, $data, 'GET', $headers);
    }

    /**
     * Helper: POST request to API
     *
     * @param  string  $routeName  API route name
     * @param  array  $data  Request data
     * @param  array  $headers  Additional headers
     * @return mixed
     */
    protected function apiPost(string $routeName, array $data = [], array $headers = []): mixed
    {
        return $this->forwardToApi($routeName, $data, 'POST', $headers);
    }

    /**
     * Helper: PUT request to API
     *
     * @param  string  $routeName  API route name
     * @param  array  $data  Request data
     * @param  array  $headers  Additional headers
     * @return mixed
     */
    protected function apiPut(string $routeName, array $data = [], array $headers = []): mixed
    {
        return $this->forwardToApi($routeName, $data, 'PUT', $headers);
    }

    /**
     * Helper: PATCH request to API
     *
     * @param  string  $routeName  API route name
     * @param  array  $data  Request data
     * @param  array  $headers  Additional headers
     * @return mixed
     */
    protected function apiPatch(string $routeName, array $data = [], array $headers = []): mixed
    {
        return $this->forwardToApi($routeName, $data, 'PATCH', $headers);
    }

    /**
     * Helper: DELETE request to API
     *
     * @param  string  $routeName  API route name
     * @param  array  $data  Request data
     * @param  array  $headers  Additional headers
     * @return mixed
     */
    protected function apiDelete(string $routeName, array $data = [], array $headers = []): mixed
    {
        return $this->forwardToApi($routeName, $data, 'DELETE', $headers);
    }

}
