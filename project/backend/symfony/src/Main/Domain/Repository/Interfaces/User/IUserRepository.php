<?php
namespace App\Main\Domain\Repository\Interfaces\User;

use App\Main\Domain\Model\User;

interface IUserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    public function save(User $user): void;

    public function update(User $user): void;

    public function delete(string $id): void;

    public function find(string $id): ?User;

    public function findByEmail(string $email): ?User;
}
