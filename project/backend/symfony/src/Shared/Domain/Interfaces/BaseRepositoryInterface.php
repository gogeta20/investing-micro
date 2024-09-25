<?php

declare(strict_types=1);

namespace App\Shared\Domain\Interfaces;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;

interface BaseRepositoryInterface
{

    public function repository(string $entityClass): EntityRepository;

    public function connection(): Connection;

    public function persist($entity): void;
    public function persistOnly($entity): void;
    public function flushOnly(): void;

    public function remove($entity): void;

}
