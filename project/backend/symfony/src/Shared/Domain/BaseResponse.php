<?php
declare(strict_types=1);
namespace App\Shared\Domain;

use App\Shared\Domain\Bus\Query\Response;

class BaseResponse implements Response
{

    protected array $data;
    protected array $response;
    protected string $message;
    protected int $status;

    public function __construct(array $param)
    {
        $this->data = $param;
        $this->response = [];
        $this->message = '';
        $this->status = 204;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function response(): array
    {
        return $this->response;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function setResponse(array $response): void
    {
        $this->response = $response;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function emptyFormat($dato, bool $primary = false): int|string|array|bool
    {
        if ($dato instanceof \DateTime) {
            return $this->dateFormat($dato);
        }
        if (is_array($dato)) {
            return empty($dato) ? [] : $dato;
        }

        if (is_bool($dato)) {
            return $dato;
        }
        if ($primary) {
            return empty($dato) ? '-' : (int)$dato;
        }
        return empty($dato) ? '-' : (string)$dato;
    }

    public function dateFormat(\DateTime|null $dato): string|null
    {
        return !$dato ? '-' : $dato->format("Y-m-d H:i:s");
    }

}
