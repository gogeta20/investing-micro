<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\GenerateDetailedReport;

use App\Main\Domain\Event\PokemonDetailsRetrievedEvent;
use App\Main\Domain\Exception\StoreException;
use App\Main\Domain\Repository\Interfaces\IPokemonService;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class GenerateDetailedReport
{
    public function __construct(
        private readonly IPokemonService $service,
        private readonly MessageBusInterface $eventBus
    ) {}

    /**
     * @throws StoreException
     */
    public function __invoke(GenerateDetailedReportCommand $command): array
    {
        try {
            file_put_contents('mauricio.txt', 'Evento recibido: '  . "\n", FILE_APPEND);
            $data = $this->service->getPokemonDetails($command->getId());
            $this->eventBus->dispatch(new PokemonDetailsRetrievedEvent(
                (string)$command->getId(),
                $data
            ));

            return ['data' => $data];
        } catch (\Exception $e) {
            throw new StoreException('Error al crear el artículo: ' . $e->getMessage());
        }
    }
}
