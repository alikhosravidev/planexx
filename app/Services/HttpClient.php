<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpFactory;

final readonly class HttpClient
{
    public function __construct(
        private HttpFactory $http
    ) {
    }

    /**
     * Send a JSON POST request and return a normalized array structure
     * similar to the previous pure-PHP client.
     */
    public function post(string $url, array $payload = [], array $headers = []): array
    {
        $response = $this->http
            ->withHeaders($headers)
            ->post($url, $payload);

        return [
            'status'  => $response->status(),
            'headers' => $response->headers(),
            'body'    => $this->safeJson($response->json()),
        ];
    }

    /**
     * Send a multipart/form-data POST request with files and fields.
     * $files should be [fieldName => filePath]
     */
    public function postMultipart(string $url, array $fields = [], array $files = [], array $headers = []): array
    {
        $request = $this->http->withHeaders($headers);

        foreach ($files as $name => $path) {
            // Read file contents; suppress warnings and let downstream handle errors
            $contents = @file_get_contents($path);
            $filename = basename($path);

            if ($contents !== false) {
                $request = $request->attach($name, $contents, $filename);
            }
        }

        $response = $request->post($url, $fields);

        return [
            'status'  => $response->status(),
            'headers' => $response->headers(),
            'body'    => $this->safeJson($response->json()),
        ];
    }

    private function safeJson(mixed $data): array
    {
        if (is_array($data)) {
            return $data;
        }

        // When response is not JSON (e.g., empty or plain text), normalize to array
        return ['raw' => is_string($data) ? $data : null];
    }
}
