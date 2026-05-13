<?php

namespace App\Infrastructure\Persistence\Storage;

use App\Application\Port\FileStorageInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final readonly class FileStorage implements FileStorageInterface
{
    private string $UPLOAD_TARGET_DIRECTORY;
    private Filesystem $filesystem;


    public function __construct(string $targetDirectory, Filesystem $filesystem)
    {
        $this->UPLOAD_TARGET_DIRECTORY = rtrim($targetDirectory, DIRECTORY_SEPARATOR);
        $this->filesystem = $filesystem;
    }

    public function saveImage(array $files, string $batchId): array
    {
        $pathsArray = [];
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();

            $dirPath = $this->UPLOAD_TARGET_DIRECTORY . DIRECTORY_SEPARATOR . $batchId;

            if (!$this->filesystem->exists($dirPath)) {
                $this->filesystem->mkdir($dirPath, 0755);
            }

            try {
                $file->move($dirPath, $fileName);
                $pathsArray[$fileName] = $batchId . DIRECTORY_SEPARATOR . $fileName;
            } catch (\Exception $e) {
                throw new FileException('Failed to save file: ' . $e->getMessage(), previous: $e);
            }
        }

        return $pathsArray;
    }

    public function deleteDirectory(string $directoryName): void
    {
        $dirPath = $this->UPLOAD_TARGET_DIRECTORY . DIRECTORY_SEPARATOR . $directoryName;

        if ($this->filesystem->exists($dirPath)) {
            try {
                $this->filesystem->remove($dirPath);
            } catch (FileException $e) {
                throw new FileException('Failed to delete directory: ' . $e->getMessage(), previous: $e);
            }
        }
    }

    function deleteFile(string $fileName, string $directoryName): void
    {
        $path = $this->UPLOAD_TARGET_DIRECTORY . DIRECTORY_SEPARATOR . $directoryName . DIRECTORY_SEPARATOR . $fileName;
        var_dump($path);
        if ($this->filesystem->exists($path)) {
            try {
                $this->filesystem->remove($path);
            } catch (FileException $e) {
                throw new FileException('Failed to delete file: ' . $e->getMessage(), previous: $e);
            }
        }
    }
}
