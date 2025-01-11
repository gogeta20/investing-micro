<?php
namespace App\Main\Infrastructure\Service;

use App\Main\Domain\Event\PokemonDetailsRetrievedEvent;
use App\Shared\Domain\ValueObject\UuidValueObject;
use TCPDF;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DetailedReportService
{
    public function __invoke(PokemonDetailsRetrievedEvent $event): void
    {
        $this->generateReport($event->getPokemonData());
    }

    public function generateReport(array $pokemonData): string
    {
        $tcpdf = new TCPDF();

        $timestamp = date('YmdHis');
        $filePath = __DIR__ . '/reporte_pokemon_' . $timestamp . '.pdf';

        $tcpdf->AddPage();
        $tcpdf->SetFont('helvetica', '', 12);
        $tcpdf->Write(0, 'Reporte de Pokemon', '', 0, 'L', true, 0, false, false, 0);

        $this->renderPokemonDataInChunks($pokemonData, $tcpdf);

        $tcpdf->Output($filePath, 'F');

        return $filePath;
    }

    private function renderPokemonDataInChunks(array $pokemonData, TCPDF $tcpdf): void
    {
        foreach ($pokemonData as $key => $value) {
            if (is_array($value)) {
                $tcpdf->Write(0, "$key:", '', 0, 'L', true, 0, false, false, 0);
                $this->renderPokemonDataInChunks($value, $tcpdf);
            } else {
                $tcpdf->Write(0, "$key: $value", '', 0, 'L', true, 0, false, false, 0);
            }

            if (memory_get_usage() > 100 * 1024 * 1024) {
                $tcpdf->AddPage();
            }
        }
    }
}
