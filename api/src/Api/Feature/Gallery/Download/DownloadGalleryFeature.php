<?php

namespace App\Api\Feature\Gallery\Download;

use App\Api\Http\Validation\RequestValidator;
use App\Application\Feature\Gallery\Download\DownloadGalleryCommand;
use App\Application\Feature\Gallery\Download\DownloadGalleryCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

class DownloadGalleryFeature extends AbstractController
{
    #[Route('api/gallery/{dir}/download', name: 'api_gallery_download', methods: ['GET'])]
    public function download(
        Request $request,
        string $dir,
        RequestValidator $validator,
        DownloadGalleryCommandHandler $handler
    ): BinaryFileResponse
    {
        $accessToken = $request->cookies->get('access_token');
        $dto = new DownloadGalleryRequest($accessToken, $dir);

        $validator->validate($dto);

        $zipPath = $handler->handle(new DownloadGalleryCommand($dto->getAccessToken(), $dto->getDir()));

        $response = new BinaryFileResponse($zipPath);

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            basename($zipPath)
        );

        return $response;
    }
}