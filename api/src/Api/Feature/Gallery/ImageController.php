<?php

namespace App\Api\Feature\Gallery;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

final class ImageController extends AbstractController
{
    #[Route('/api/photos/{dir}/{name}', name: 'photo_show', methods: ['GET'])]
    public function show(
        string $dir,
        string $name,
        LoggerInterface $appLogger,
    ): BinaryFileResponse {
        $path = '/var/pixario/uploads/' . $dir . "/" . $name;

        $appLogger->info($path);

        $response = new BinaryFileResponse($path);

        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, $name);

        return $response;
    }
}