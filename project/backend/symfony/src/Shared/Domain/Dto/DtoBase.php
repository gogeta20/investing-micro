<?php

namespace App\Shared\Domain\Dto;

use Integraciones\Shared\Infrastructure\Repository\FileLoginRepository;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class DtoBase
{
    public function __construct(
        protected RequestStack $requestStack,
        protected FileLoginRepository $fileLoginRepository
    ) {}

    abstract protected function text(): string;
}
