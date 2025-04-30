<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PhotoRoomService
{
    private HttpClientInterface $client;
    private string $apiKey;
    private string $apiUrl;
    private LoggerInterface $logger;

    public function __construct(
        HttpClientInterface $client,
        string              $photoRoomApiUrl,
        string              $photoRoomApiKey,
        LoggerInterface     $logger
    )
    {
        $this->client = $client;
        $this->apiKey = $photoRoomApiKey;
        $this->apiUrl = $photoRoomApiUrl;
        $this->logger = $logger;
    }

    public function retouchImage(UploadedFile $image): string
    {
        try {
            $response = $this->client->request('POST', $this->apiUrl, [
                'headers' => [
                    'x-api-key' => $this->apiKey,
                    'Accept' => 'image/png',
                ],
                'body' => [
                    'image-file' => fopen($image->getPathname(), 'rb')
                ],
            ]);

            if (200 !== $response->getStatusCode()) {
                throw new HttpException($response->getStatusCode(), 'PhotoRoom API failed.');
            }

            return $response->getContent(false);
        } catch (TransportExceptionInterface|ClientExceptionInterface|ServerExceptionInterface $e) {
            $this->logger->error('PhotoRoom API error: ' . $e->getMessage());
            throw new HttpException(500, 'Failed to retouch image.');
        }
    }
}