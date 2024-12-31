<?php

declare(strict_types=1);

namespace App\Shared\Domain;

interface IRepository
{

    public function repository(string $entityClass);

    public function persist($entity): void;

    public function remove($entity): void;

}
