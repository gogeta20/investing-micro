<?php

namespace App\Shared\Domain\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface ParamsRequestInterface
{
    public function requestParameters(Request $request): array;

    public function requestParametersBody(Request $request);

    public function idSession(Request $request): string;
}
