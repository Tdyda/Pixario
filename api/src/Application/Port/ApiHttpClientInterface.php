<?php

namespace App\Application\Port;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface ApiHttpClientInterface
{
    public function request(string $method, string $url, array $options = []): array;

    public function postAsJson(string $url, array $data = [], ?float $timeout = 30.0): array;

    function get(string $url, array $query = [], ?float $timeout = 30.0): array;

    function handleResponse(ResponseInterface $response): array;
}