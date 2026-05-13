<?php

namespace App\Infrastructure\HttpClient;

use App\Application\Port\ApiHttpClientInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final readonly class ApiHttpClient implements ApiHttpClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {
    }

    public function postAsJson(
        string $url,
        array $data = [],
        ?float $timeout = 30.0,
    ): array {
        $response = $this->httpClient->request(
            'POST',
            $url,
            [
                'json' => $data,
                'timeout' => $timeout,
            ]
        );

        return $this->handleResponse($response);
    }

    public function request(
        string $method,
        string $url,
        array $options = [],
    ): array {
        $response = $this->httpClient->request($method, $url, $options);

        return $this->handleResponse($response);
    }

    function handleResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400) {
            throw new \RuntimeException(
                sprintf('External API request failed with status code %d', $statusCode)
            );
        }

        return $response->toArray();
    }

    public function get(
        string $url,
        array $query = [],
        ?float $timeout = 30.0,
    ): array {
        $response = $this->httpClient->request(
            'GET',
            $url,
            [
                'query' => $query,
                'timeout' => $timeout,
            ]
        );

        return $this->handleResponse($response);
    }
}