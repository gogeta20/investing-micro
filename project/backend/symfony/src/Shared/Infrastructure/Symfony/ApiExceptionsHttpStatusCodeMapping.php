<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use ErrorException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Lambdish\Phunctional\get;

class ApiExceptionsHttpStatusCodeMapping
{
    private const DEFAULT_STATUS_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;
    private array $exceptions = [
        InvalidArgumentException::class => Response::HTTP_BAD_REQUEST,
        NotFoundHttpException::class => Response::HTTP_NOT_FOUND,
        ErrorException::class => Response::HTTP_BAD_REQUEST
    ];

    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function register(string $exceptionClass, int $statusCode): void
    {
        $this->exceptions[$exceptionClass] = $statusCode;
    }

    public function statusCodeFor(string $exceptionClass): int
    {
        $statusCode = get($exceptionClass, $this->exceptions, self::DEFAULT_STATUS_CODE);
        if ($statusCode === null) {
            throw new InvalidArgumentException(
                $this->translator->trans("Non existe mapeo para o código de estado ") . "
                                               <$exceptionClass>"
            );
        }
        return $statusCode;
    }
}
