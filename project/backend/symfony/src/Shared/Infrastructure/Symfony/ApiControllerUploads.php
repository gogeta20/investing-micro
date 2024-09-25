<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use App\Main\Infrastructure\Service\TokenDecoderService;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;
use App\Shared\Infrastructure\Service\FileUploaderService;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class ApiControllerUploads extends ApiControllerBasic
{
    protected FileUploaderService $fileUploader;

    public function __construct(
        QueryBus $queryBus,
        CommandBus $commandBus,
        TranslateInterfaceCustom         $translator,
        ApiExceptionsHttpStatusCodeMapping $exceptionHandler,
        protected readonly TokenDecoderService     $decoderService,
        FileUploaderService $fileUploader
    ) {
        parent::__construct($queryBus, $commandBus, $translator, $exceptionHandler);
        $this->fileUploader = $fileUploader;
    }

    protected function uploadFile(UploadedFile $file, string $directory): string
    {
        return $this->fileUploader->upload($file, $directory);
    }
}
