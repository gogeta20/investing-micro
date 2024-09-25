<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use Symfony\Component\HttpFoundation\JsonResponse;
use function is_array;

final class StandardApiResponse
{
    public function __construct(
        protected ?array $data,
        private ?string  $message,
        private ?int     $code,
    )
    {
        if (!is_array($this->data)) {
            $this->data = [];
        }
        if ($this->message === null) {
            $this->message = '';
        }
        if ($this->code === null) {
            $this->code = 200;
        }
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->data,
            'message' => $this->message,
            'status' => $this->code
        ], $this->code);
    }
}
