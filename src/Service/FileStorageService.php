<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileStorageService
{
    private string $targetDirectory;
    private Filesystem $filesystem;

    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = rtrim($targetDirectory, '/');
        $this->filesystem = new Filesystem();
    }

    public function saveImage(string $base64Image, string $extension = 'jpg'): string
    {
        // Usuń prefix typu data:image/jpeg;base64, jeśli występuje
        if (str_starts_with($base64Image, 'data:')) {
            $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);
        }

        $binaryData = base64_decode($base64Image);

        if ($binaryData === false) {
            throw new FileException('Invalid base64 image data.');
        }

        $fileName = uniqid('photo_', true) . '.' . $extension;
        $fullPath = $this->targetDirectory . '/' . $fileName;

        if (!$this->filesystem->exists($this->targetDirectory)) {
            $this->filesystem->mkdir($this->targetDirectory, 0755);
        }

        try {
            file_put_contents($fullPath, $binaryData);
        } catch (\Exception $e) {
            throw new FileException('Failed to save file: ' . $e->getMessage());
        }

        return 'uploads/retouched/' . $fileName;
    }
}
