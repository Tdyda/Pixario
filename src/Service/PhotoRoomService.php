<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PhotoRoomService
{
    private string $apiKey;
    private string $apiUrl;
    private LoggerInterface $logger;

    public function __construct(
        string          $photoRoomApiUrl,
        string          $photoRoomApiKey,
        LoggerInterface $logger
    )
    {
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
            "--$boundary\r\n" .
            "Content-Disposition: form-data; name=\"beauty_level\"\r\n\r\n" .
            "0.4\r\n" .
            "--$boundary\r\n" .
            "Content-Disposition: form-data; name=\"task_type\"\r\n\r\n" .
            "sync\r\n" .
            "--$boundary\r\n" .
            "Content-Disposition: form-data; name=\"multi_face\"\r\n\r\n" .
            "1\r\n" .
            "--$boundary--\r\n";

        try {
            $response = $client->request('POST', $this->apiUrl, [
                'headers' => [
                    'ailabapi-api-key' => $this->apiKey,
                    'Content-Type' => 'multipart/form-data; boundary=' . $boundary,
                ],
                'body' => $body,
            ]);

            if ($response->getStatusCode() !== 200) {
                $this->logger->error('AILabTools response error: ' . $response->getStatusCode());
                throw new HttpException($response->getStatusCode(), 'AILabTools API failed.');
            }

            $content = $response->toArray(false);

            if (!isset($content['data']['image'])) {
                throw new HttpException(500, 'AILabTools returned invalid response.');
            }

            return $content['data']['image']; // base64
        } catch (TransportExceptionInterface $e) {
            $this->logger->error('AILabTools API error: ' . $e->getMessage());
            throw new HttpException(500, 'Failed to retouch image. Error: ' . $e->getMessage());
        }
    }
}
