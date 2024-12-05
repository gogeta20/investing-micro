<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\GenerateDetailedReport;

use App\Shared\Domain\Bus\Command\Command;

readonly class GenerateDetailedReportCommand implements Command
{
    public function __construct(private int $id) {}

    public static function create(array $data): self
    {
        return new self(
            $data['id'],
        );
    }

    public function getId(): int
    {
        return $this->id;
    }
}
