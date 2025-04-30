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
        $this->targetDirectory = $targetDirectory;
        $this->filesystem = new Filesystem();
    }

    public function saveImage(string $imageData, string $extension = 'jpg')
    {
        $fileName = uniqid('photo_', true) . '.' . $extension;
        $fullPath = $this->targetDirectory . '/' . $fileName;

        if(!$this->filesystem->exists($this->targetDirectory)) {
            $this->filesystem->mkdir($this->targetDirectory, 0755);
        }

        try{
            file_put_contents($fullPath, $imageData);
        } catch (\Exception $e) {
            throw new FileException('Failed to save file: ' . $e->getMessage());
        }

        return 'uplaods/retouched' . $fileName;
    }
}