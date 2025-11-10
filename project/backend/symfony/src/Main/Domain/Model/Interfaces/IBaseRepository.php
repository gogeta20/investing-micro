<?php

declare(strict_types=1);

namespace App\Main\Domain\Model\Interfaces;

interface IBaseRepository
{
    public function save(IEntity $iEntity): void;
    // public function get(string $id): IEntity;
}
