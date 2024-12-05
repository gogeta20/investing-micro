<?php
namespace App\Main\Domain\Repository\Interfaces;

interface IDetailedReportService
{
    public function generateReport(array $pokemonData): string;
}
