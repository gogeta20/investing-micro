<?php
declare(strict_types=1);
namespace App\Main\Infrastructure\Storage;

use App\Main\Domain\Model\EstadisticasBase;
use App\Main\Domain\Model\Interfaces\IEstadisticasBase;
use App\Shared\Infrastructure\BaseRepository;

final class EstadisticasBaseRepository extends BaseRepository implements IEstadisticasBase
{
    public function save(EstadisticasBase $estadisticasBase): void
    {
        $this->persist($estadisticasBase);
    }
}
