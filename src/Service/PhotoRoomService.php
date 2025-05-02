<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;

class PhotoRoomService
{
    private string $apiKey;
    private string $apiUrl;
    private LoggerInterface $logger;

    public function __construct(
        string $photoRoomApiUrl,
        string $photoRoomApiKey,
        LoggerInterface $logger
    ) {
        $this->apiKey = $photoRoomApiKey;
        $this->apiUrl = $photoRoomApiUrl;
        $this->logger = $logger;
    }

    public function retouchImage(UploadedFile $image): string
    {
        $client = HttpClient::create();
        $boundary = '----PixarioBoundary' . md5(uniqid());

        $fileContent = file_get_contents($image->getPathname());
        $filename = $image->getClientOriginalName();
        $mime = $image->getMimeType();

        $body =
            "--$boundary\r\n" .
            "Content-Disposition: form-data; name=\"image_target\"; filename=\"$filename\"\r\n" .
            "Content-Type: $mime\r\n\r\n" .
            $fileContent . "\r\n" .
            "--$boundary--\r\n";

        try {
            $response = $client->request('POST', $this->apiUrl, [
                'headers' => [
                    'x-api-key' => $this->apiKey,
                    'Accept' => 'image/png',
                    'Content-Type' => 'multipart/form-data; boundary=' . $boundary,
                ],
                'body' => $body,
            ]);

            if ($response->getStatusCode() !== 200) {
                $this->logger->error('PhotoRoom response error: ' . $response->getStatusCode());
                throw new HttpException($response->getStatusCode(), 'PhotoRoom API failed.');
            }

            return $response->getContent(false);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('PhotoRoom API error: ' . $e->getMessage());
            throw new HttpException(500, 'Failed to retouch image. Error: ' . $e->getMessage());
        }
    }
}
