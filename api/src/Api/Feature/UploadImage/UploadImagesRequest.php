<?php

namespace App\Api\Feature\UploadImage;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final class UploadImagesRequest
{
    #[Assert\Count(min: 1, minMessage: 'At least one photo is required.')]
    #[Assert\All([
        new Assert\File(
            maxSize: '10M',
            mimeTypes: ['image/jpeg', 'image/png'],
            maxSizeMessage: 'The file is too large ({{ size }} {{ suffix }}). Maximum allowed size is {{ limit }} {{ suffix }}.',
            mimeTypesMessage: 'Please upload a valid JPEG or PNG image.'
        )
    ])]
    public array $images;

    public function __construct(array $images = [])
    {
        $this->images = $images;
    }

    /**
     * @return UploadedFile[]
     */
    public function getImages(): array
    {
        return $this->images;
    }
}