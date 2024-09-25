<?php

namespace App\Shared\Infrastructure\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Ramsey\Uuid\Uuid;

class FileUploaderService
{
    private string $targetDirectory;

    public function __construct(string $projectDir)
    {
        $this->targetDirectory = $projectDir;
    }

    public function upload(UploadedFile $file, string $directory): string
    {
        $filename = Uuid::uuid4()->toString() . '.' . $file->guessExtension();

        try {
            $file->move($this->targetDirectory . '/' . $directory, $filename);
        } catch (FileException $e) {
            throw new \RuntimeException('No se pudo mover el archivo subido: ' . $e->getMessage());
        }

        return $directory . '/' . $filename;
    }
}
