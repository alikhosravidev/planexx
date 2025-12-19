<?php

declare(strict_types=1);

namespace Applications\Contracts;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
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
 * TODO: test
 */
abstract class BaseWebController
{
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
        $originalRequest = request();

        try {
            $routeParams = [];
            $route       = $this->resolveApiRoute($routeName);
            $payload     = $this->extractPayload($route, $data, $routeParams);
            $subRequest  = $this->buildSubRequest($routeName, $routeParams, $payload, $method, $headers);

            $response        = app(Kernel::class)->handle($subRequest);
            $decodedResponse = json_decode($response->getContent(), true);
            $statusCode      = $response->getStatusCode();

            if ($statusCode >= 400) {
                Log::error('API Error in BaseWebController', [
                    'route'        => $routeName,
                    'method'       => $method,
                    'status'       => $statusCode,
                    'response'     => $decodedResponse,
                    'request_data' => $data,
                ]);
            }
            $this->handleValidationErrors($statusCode, $decodedResponse, $data);
            $this->handleOtherErrors($statusCode, $decodedResponse);

            return $decodedResponse;
        } finally {
            app()->instance('request', $originalRequest);
        }
    }

    private function resolveApiRoute(string $routeName)
    {
        $route = Route::getRoutes()->getByName($routeName);

        if (!$route) {
            throw new RuntimeException("API route '{$routeName}' not found");
        }

        return $route;
    }

    private function extractPayload($route, array $data, array &$routeParams): array
    {
        $paramNames  = $route->parameterNames();
        $routeParams = array_intersect_key($data, array_flip($paramNames));

        return array_diff_key($data, $routeParams);
    }

    private function buildSubRequest(
        string $routeName,
        array $routeParams,
        array $payload,
        string $method,
        array $headers
    ): Request {
        $generatedUrl = app('url')->route($routeName, $routeParams, false);
        $uri          = parse_url($generatedUrl, PHP_URL_PATH) ?: $generatedUrl;

        $subRequest = Request::create(
            uri: $uri,
            method: $method,
            parameters: $payload,
            server: request()->server->all()
        );

        $subRequest->headers->set('Accept', 'application/json');

        foreach ($headers as $key => $value) {
            $subRequest->headers->set($key, $value);
        }

        $this->ensureAuthorizationHeader($subRequest);

        return $subRequest;
    }

    private function ensureAuthorizationHeader(Request $subRequest): void
    {
        if ($subRequest->headers->has('Authorization')) {
            return;
        }

        $token = request()->cookie('token') ?? request()->bearerToken();

        if (!empty($token)) {
            $subRequest->headers->set('Authorization', 'Bearer ' . $token);
        }
    }

    private function handleValidationErrors(int $statusCode, ?array $decodedResponse, array $data): void
    {
        if ($statusCode !== 422) {
            return;
        }

        Session::flashInput($data);

        throw ValidationException::withMessages($decodedResponse['errors'] ?? []);
    }

    private function handleOtherErrors(int $statusCode, ?array $decodedResponse): void
    {
        if ($statusCode < 400) {
            return;
        }

        // For all other errors (500, 404, 403, etc.), throw an exception with details
        $message   = $decodedResponse['message']   ?? "API request failed with status {$statusCode}";
        $exception = $decodedResponse['exception'] ?? null;

        if ($exception && config('app.debug')) {
            throw new RuntimeException("{$message}: {$exception}");
        }

        throw new RuntimeException($message);
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
