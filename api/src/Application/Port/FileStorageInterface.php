<?php

namespace App\Application\Port;

interface FileStorageInterface
{
    function saveImage(array $files, string $batchId): array;

    function deleteDirectory(string $directoryName): void;

    function deleteFile(string $fileName, string $directoryName): void;
}