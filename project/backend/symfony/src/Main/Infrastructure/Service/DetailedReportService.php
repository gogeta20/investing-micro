<?php
namespace App\Main\Infrastructure\Service;

use Dompdf\Dompdf;
use App\Main\Domain\Repository\Interfaces\IDetailedReportService;

class DetailedReportService implements IDetailedReportService
{
    public function generateReport(array $pokemonData): string
    {
        $dompdf = new Dompdf();
        // Construir el contenido del HTML basado en los datos del Pokémon
        $html = $this->renderPokemonData($pokemonData);
        // Aquí puedes agregar más información según sea necesario

        // Cargar el HTML en Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Guardar o devolver el PDF generado
        $output = $dompdf->output();
        file_put_contents('reporte_pokemon.pdf', $output);

        return 'reporte_pokemon.pdf';  // Devolvemos el nombre del archivo generado
    }

    function renderPokemonData(array $pokemonData): string {
        $html = '';

        foreach ($pokemonData as $key => $value) {
            // Si el valor es un array, hacemos una llamada recursiva
            if (is_array($value)) {
                $html .= "<p><strong>$key</strong>:</p>";
                $html .= $this->renderPokemonData($value);  // Recursión para manejar arrays anidados
            } else {
                $html .= "<p>$key: $value</p>";  // Si no es un array, simplemente mostramos la clave y el valor
            }
        }

        return $html;
    }
}
