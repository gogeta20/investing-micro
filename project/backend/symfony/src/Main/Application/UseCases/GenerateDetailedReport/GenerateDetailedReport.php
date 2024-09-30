<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\GenerateDetailedReport;

use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Repository\Interfaces\IPokemonService;

readonly class GenerateDetailedReport
{
    public function __construct(
        private readonly IPokemonService $service,
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(GenerateDetailedReportCommand $command): array
    {
        try {
            $user = $this->service->getPokemonDetails('picachu');
            return ['article' => $user];
        } catch (\Exception $e) {
            throw new StoreException('Error al crear el artículo: ' . $e->getMessage());
        }
    }
}
