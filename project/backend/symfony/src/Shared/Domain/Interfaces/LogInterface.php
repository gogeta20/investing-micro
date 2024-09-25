<?php

namespace App\Shared\Domain\Interfaces;

use Stringable;

interface LogInterface
{

    public function startQuery();

    public function finishQuery();

    public function logger(
        string          $tipo,
        \Exception|bool $error = false,
        string|bool     $consulta = false,
        string|bool     $database = false
    );

    public function emergency(Stringable|string $message, array $context = []): void;

    public function alert(Stringable|string $message, array $context = []): void;

    public function critical(Stringable|string $message, array $context = []): void;

    public function error(Stringable|string $message, array $context = []): void;

    public function warning(Stringable|string $message, array $context = []): void;

    public function notice(Stringable|string $message, array $context = []): void;

    public function info(Stringable|string $message, array $context = []): void;

    public function debug(Stringable|string $message, array $context = []): void;

    public function log($level, Stringable|string $message, array $context = []): void;

    public function registerRows($rows): void;
}
