<?php
declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\User\Edit;

use App\Shared\Domain\Bus\Command\Command;

readonly class EditUserCommand implements Command
{
    public function __construct(
        private ?string $email = null,
        private ?string $newEmail = null,
        private ?string $username = null,
        private ?string $password = null
    ) {}

    public static function create(array $data): self
    {
        return new self(
            $data['email'] ?? null,
            $data['new_email'] ?? null,
            $data['username'] ?? null,
            $data['password'] ?? null
        );
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function newEmail(): ?string
    {
        return $this->newEmail;
    }

    public function username(): ?string
    {
        return $this->username;
    }

    public function password(): ?string
    {
        return $this->password;
    }
}
