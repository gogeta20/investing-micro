<?php

declare(strict_types=1);

namespace App\Main\Infrastructure\Request;

interface RequestInterface
{
    public function dataRequest(): array;

    public function dataFull(): array;

    public function data(): array;

    public function routeParams(): array;

    public function filters(): array;

    public function locale(): string;

    public function files(): array;

    public function getHost(): string;
}
