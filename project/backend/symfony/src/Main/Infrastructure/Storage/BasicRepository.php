<?php
declare(strict_types=1);
namespace App\Main\Infrastructure\Storage;

use App\Main\Domain\Model\Interfaces\IBaseRepository;
use App\Shared\Infrastructure\BaseRepository;
use App\Main\Domain\Model\Interfaces\IEntity;

final class BasicRepository extends BaseRepository implements IBaseRepository
{
    public function save(IEntity $entity): void
    {
        $this->persist($entity);
    }
}
