<?php

declare(strict_types=1);

namespace App\Shared\Domain\Interfaces;

interface InformeInterface
{
    public function getIdxf(): int;

    public function getCodTipo(): string;

    public function getEstado(): string;

    public function getDescripcion(int $idxf, string $codTipoInforme, string $instrumentoCodigoEstado, string $instrumentoEstado): string;

    public function getNombreFichero(int $idxf, string $codTipoInforme, string $instrumentoCodigoEstado): string;

    public function checkSegunTipoInstrumento(int $idxf): bool;
}
