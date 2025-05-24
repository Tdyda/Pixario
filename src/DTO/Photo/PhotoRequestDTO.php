<?php

namespace App\DTO\Photo;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class PhotoRequestDTO
{
    #[Assert\NotNull(message: "Photo file is required.")]
    #[Assert\File(
        maxSize: "10M",
        mimeTypes: ["image/jpeg", "image/png"],
        maxSizeMessage: "The file is too large ({{ size }} {{ suffix }}). Maximum allowed size is {{ limit }} {{ suffix }}.",
        mimeTypesMessage: "Please upload a valid JPEG or PNG image."
    )]
    public ?UploadedFile $photo = null;

    public function __construct(?UploadedFile $photo = null)
    {
        $this->photo = $photo;
    }

    public function getPhoto(): ?UploadedFile
    {
        return $this->photo;
    }

    public function setPhoto(?UploadedFile $photo): void
    {
        $this->photo = $photo;
    }
}