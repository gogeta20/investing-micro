<?php

declare(strict_types=1);

namespace App\Shared\Tools;

use function Lambdish\Phunctional\reverse;

class DateTool
{
    function dateTime_es_ES(\DateTime $dateTime): string
    {
        return $dateTime->format('d/m/Y H:i:s');
    }

    function dateFormatCirbe(\DateTime $value): string
    {
        return $value->format('d/m/Y');
    }

    function calculateDomiciliationDays(int $filesCount = 11, int $daysBefore = 2): array
    {
        $dayMargin = 2;
        $day = (int)date('d');
        $month = (int)date('m');
        $daysMonth = [];

        if ($day > 11) {
            ++$month;
        }

        $check = new \DateTime(date('Y-' . $month . '-0' . ($filesCount - $daysBefore - $dayMargin)));
        $count = 0;
        while ($count < $filesCount) {
            while (isWeekend($check) || isSepaHoliday($check)) {
                $check->modify('-1 day');
            }

            $daysMonth[] = clone $check;
            $check->modify('-1 day');
            ++$count;
        }

        return reverse($daysMonth);
    }

    function isWeekend(\DateTime $date = null): bool
    {
        if (!$date instanceof \DateTime) {
            $date = new \DateTime();
        }

        return date('N', (int)strtotime($date->format('Y-m-d'))) >= 6;
    }

    function isSepaHoliday(\DateTime $date = null): bool
    {
        if (!$date instanceof \DateTime) {
            $date = new \DateTime();
        }

        $sepaFreeDays = json_decode($_ENV['SEPA_FREE_DAYS']);

        foreach ($sepaFreeDays as $dayString) {
            $line = str_replace("\n", '', $dayString);
            if ($line === $date->format('d-m-Y')) {
                return true;
            }
        }

        return false;
    }
}
