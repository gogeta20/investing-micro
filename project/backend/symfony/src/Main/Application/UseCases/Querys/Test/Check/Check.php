<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Querys\Test\Check;

use App\Main\Domain\Repository\Interfaces\User\IUserRepository;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;

final class Check
{

    public function __construct(
        protected TranslateInterfaceCustom $translatorCustom,
        private readonly IUserRepository   $repository,
    )
    {
    }

    public function __invoke(CheckQuery $query): array
    {
        if($query->email() !== null){
            $user = $this->repository->findByEmail($query->email());
            return ["token"=>$user->getToken()];
        }
        return ["name"=> "check"];
    }
}
