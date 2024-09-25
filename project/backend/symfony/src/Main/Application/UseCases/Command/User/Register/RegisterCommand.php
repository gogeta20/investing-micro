<?php
declare(strict_types=1);

namespace App\Main\Application\UseCases\Command\User\Register;

use App\Shared\Domain\Bus\Command\Command;

readonly class RegisterCommand implements Command
{
    public function __construct(
        private string $email,
        private string $password
    ) {}


    public static function create(array $data): self
    {
        return new self(
            $data['email'],
            $data['password'],
        );
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }
}
