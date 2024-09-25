<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony;

use App\Shared\Domain\DomainError;
use App\Shared\Infrastructure\Log\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Contracts\Translation\TranslatorInterface;
use Throwable;
use function preg_replace;

final class ApiExceptionListener
{
    public function __construct(
        private ApiExceptionsHttpStatusCodeMapping $exceptionHandler,
        private TranslatorInterface                $translator,
        private Log $log
    ) {
    }

    public function onException(ExceptionEvent $event): void
    {

        $exception = $event->getThrowable();
        $messageLog = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();

        $messageLog = $messageLog . ' - ' . $file .' linea '.$line;
        $this->log->emergency($messageLog, [$exception]);
        $headers = $event->getRequest()->headers;
        if ($headers->get('content-type') != "text/xml;charset=UTF-8") {
            $statusCode = (int)$this->exceptionCodeFor($exception);
            if (empty($statusCode)) {
                $statusCode = 500;
            }

            $event->setResponse(
                new JsonResponse(
                    [
                        'status' => $statusCode,
                        'data' => [],
                        'message' => $this->translator->trans(
                            preg_replace(
                                '/^Handling.*failed:\s/',
                                '',
                                $exception->getMessage()
                            )
                        ),
                    ],
                    // $this->exceptionHandler->statusCodeFor($exception::class)
                    $statusCode
                )
            );
        }
    }

    private function exceptionCodeFor(Throwable $error): string
    {
        if ($previous = $error->getPrevious()) {
            return (string)$this->exceptionHandler->statusCodeFor($previous::class);
        }

        $domainErrorClass = DomainError::class;
        $code = $error->getCode();
        return $error instanceof $domainErrorClass ? $error->getMessage(): (string)$code;
    }
}
