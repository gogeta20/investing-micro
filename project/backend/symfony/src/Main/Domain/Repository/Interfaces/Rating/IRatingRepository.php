<?php
namespace App\Main\Domain\Repository\Interfaces\Rating;

use App\Main\Domain\Model\Rating;

interface IRatingRepository
{
    /**
     * @return Rating[]
     */
    public function findAll(): array;

    public function save(Rating $user): void;

    public function update(Rating $user): void;

    public function delete(string $id): void;

    public function find(string $id): ?Rating;

    public function findByEmail(string $email): ?Rating; // Para buscar usuarios por email durante el login
}
