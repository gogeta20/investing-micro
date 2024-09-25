<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

abstract class DateValueObject
{
    /**
     * @param DateTime|DateTimeImmutable $dateTime
     */
    public function __construct(protected DateTimeInterface $dateTime)
    {
    }

    /**
     * @return DateTime|DateTimeImmutable
     */
    public function value(): DateTimeInterface
    {
        return $this->dateTime;
    }

    public function getYMD(): string
    {
        return $this->dateTime->format('Y-m-d');
    }
}
