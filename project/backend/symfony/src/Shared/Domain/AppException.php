<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use Error;
use Exception;
use Throwable;

use function method_exists;
use function preg_replace;

class AppException extends Exception
{
    public function __construct(
        string               $message = '',
        int                  $code = 0,
        Throwable $exception = null
    ) {
        if ($exception !== null) {
            $code = $exception->getCode();
            $previous = $exception->getPrevious();
        }
        if ($message !== '') {
            $message = preg_replace('/(Exception:\s)+/', '', $message);
        } else if ($exception !== null){
            $message = $exception->getMessage();
        }
        parent::__construct($message, $code, $exception);
    }
}
