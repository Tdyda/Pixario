<?php

namespace App\Application\Feature\UploadImage;

use App\Api\Feature\UploadImage\UploadImagesResponse;
use App\Application\Port\ApiHttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

final readonly class UploadImagesCommandHandler
{
    public function __construct(
        private ApiHttpClientInterface $apiHttpClient,
        #[Autowire('%env(PIXARIO_API_KEY)%')]
        private string $pixarioApiKey,
        #[Autowire('%env(PIXARIO_INGEST_URL)%')]
        private string $baseUrl,
    ) {
    }

    public function handle(UploadImagesCommand $command): UploadImagesResponse
    {
        $formData = new FormDataPart([
            'galleryId' => $command->galleryId,
            'images[]' => array_map(
                static fn(UploadedFile $image) => DataPart::fromPath(
                    $image->getPathname(),
                    $image->getClientOriginalName(),
                    $image->getMimeType() ?? 'application/octet-stream'
                ),
                $command->images
            ),
        ]);

        $headers = $formData->getPreparedHeaders()->toArray();

        $headers['x-api-key'] = $this->pixarioApiKey;

        $response = $this->apiHttpClient->request('POST', $this->baseUrl . '/uploads/images', [
            'headers' => $headers,
            'body' => $formData->bodyToIterable(),
            'timeout' => 30,
        ]);

        return new UploadImagesResponse(
            $response['jobId']
        );
    }
}