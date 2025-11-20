<?php

declare(strict_types=1);

namespace App\Contracts\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;

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
     * API prefix for internal requests
     *
     * In this project, API routes are registered WITHOUT the /api prefix.
     * Routes are directly accessible like: /location/addresses, /organization/departments
     *
     * If your project uses /api prefix, change this to '/api'
     */
    protected string $apiPrefix = '';

    /**
     * Forwards an internal request to the application's API.
     *
     * This method creates a complete request simulation that passes through
     * the entire HTTP kernel, ensuring all middleware (auth, validation, etc.)
     * is properly executed.
     *
     * @param  string  $method  HTTP Method (GET, POST, PUT, PATCH, DELETE)
     * @param  string  $endpoint  The API endpoint (e.g., '/location/addresses', '/organization/departments/1')
     * @param  array  $data  Data payload for the request
     * @param  array  $headers  Additional headers (optional)
     * @return mixed Decoded JSON response from the API
     *
     * @throws ValidationException|\Exception When API returns 422 validation errors
     */
    protected function forwardToApi(
        string $method,
        string $endpoint,
        array $data = [],
        array $headers = []
    ): mixed {
        // 1. Create a new request object with proper URI structure
        $uri = $this->buildApiUri($endpoint);

        $request = Request::create(
            uri: $uri,
            method: $method,
            parameters: $data,
            server: request()->server->all() // Forward server variables
        );

        // 2. Set headers to simulate a real API client
        $request->headers->set('Accept', 'application/json');

        // Add any additional custom headers
        foreach ($headers as $key => $value) {
            $request->headers->set($key, $value);
        }

        // 3. --- CRITICAL: Authentication Forwarding ---
        // Get the currently logged-in user from the 'web' guard (session)
        $webUser = auth('web')->user();

        if ($webUser instanceof Authenticatable) {
            // Tell Sanctum to "act as" this user for this internal request.
            // This bridges the gap between session-based 'web' auth and token-based 'api' auth.
            Sanctum::actingAs($webUser, abilities: ['*'], guard: 'sanctum');
        }

        // 4. --- CRITICAL: Dispatch Request via HTTP Kernel ---
        // We use app()->handle() to send the request through the *entire*
        // HTTP Kernel, including all global and route-specific middleware.
        // This is a full simulation of an external HTTP request.
        $response = app()->handle($request);

        // 5. Clean up authentication state (optional but good practice)
        if ($webUser instanceof Authenticatable) {
            Sanctum::actingAs(null, guard: 'sanctum');
        }

        // 6. Decode the JSON response
        $decodedResponse = json_decode($response->getContent(), true);

        // 7. --- Automatic Validation Error Handling ---
        // If the API returns a 422 (Validation Failed),
        // automatically re-throw it as a web ValidationException
        // to redirect back to the form with errors.
        if ($response->getStatusCode() === 422) {
            // Flash old input to the session so users don't lose their data
            Session::flashInput($data);

            throw ValidationException::withMessages($decodedResponse['errors'] ?? []);
        }

        // 8. Handle other error status codes if needed
        // You can add more status code handling here based on your needs
        // For example: 401, 403, 404, 500, etc.

        return $decodedResponse;
    }

    /**
     * Build the complete API URI with proper prefix
     *
     * @param  string  $endpoint  The endpoint path
     * @return string Complete URI
     */
    protected function buildApiUri(string $endpoint): string
    {
        $endpoint = ltrim($endpoint, '/');

        if (empty($this->apiPrefix)) {
            return '/' . $endpoint;
        }

        return '/' . trim($this->apiPrefix, '/') . '/' . $endpoint;
    }

    /**
     * Helper method for GET requests
     *
     * @param  array  $params  Query parameters
     *
     * @throws ValidationException|\Exception
     */
    protected function apiGet(string $endpoint, array $params = [], array $headers = []): mixed
    {
        return $this->forwardToApi('GET', $endpoint, $params, $headers);
    }

    /**
     * Helper method for POST requests
     *
     * @throws ValidationException|\Exception
     */
    protected function apiPost(string $endpoint, array $data = [], array $headers = []): mixed
    {
        return $this->forwardToApi('POST', $endpoint, $data, $headers);
    }

    /**
     * Helper method for PUT requests
     *
     * @throws ValidationException|\Exception
     */
    protected function apiPut(string $endpoint, array $data = [], array $headers = []): mixed
    {
        return $this->forwardToApi('PUT', $endpoint, $data, $headers);
    }

    /**
     * Helper method for PATCH requests
     *
     * @throws ValidationException|\Exception
     */
    protected function apiPatch(string $endpoint, array $data = [], array $headers = []): mixed
    {
        return $this->forwardToApi('PATCH', $endpoint, $data, $headers);
    }

    /**
     * Helper method for DELETE requests
     *
     * @throws ValidationException|\Exception
     */
    protected function apiDelete(string $endpoint, array $data = [], array $headers = []): mixed
    {
        return $this->forwardToApi('DELETE', $endpoint, $data, $headers);
    }
}
