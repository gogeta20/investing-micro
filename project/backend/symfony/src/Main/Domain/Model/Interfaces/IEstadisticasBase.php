<?php

declare(strict_types=1);

namespace App\Main\Domain\Model\Interfaces;
use App\Main\Domain\Model\EstadisticasBase;

interface IEstadisticasBase
{
    public function save(EstadisticasBase $estadisticasBase): void;
}
