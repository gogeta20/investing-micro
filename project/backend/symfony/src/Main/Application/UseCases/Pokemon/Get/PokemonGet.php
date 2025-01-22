<?php

declare(strict_types=1);

namespace App\Main\Application\UseCases\Pokemon\Get;

use App\Main\Domain\Model\Mongo\PokemonBase;
use App\Shared\Infrastructure\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Shared\Domain\Interfaces\TranslateInterfaceCustom;

final class PokemonGet extends DocumentRepository
{
    public function __construct(
        protected DocumentManager $documentManager,
        protected TranslateInterfaceCustom $translatorCustom,
    )  {
        parent::__construct($this->documentManager);
    }

    public function __invoke(PokemonGetQuery $query): array
    {
        $repository = $this->repository(PokemonBase::class);

        $lastPokemon = $repository->findOneBy([],['numero_pokedex' => 'DESC'] );

        if (!$lastPokemon) {
            return [
                "name" => "PokemonGet",
                "data" => [],
            ];
        }

        return [
            'numeroPokedex' => $lastPokemon->getNumeroPokedex(),
            'nombre' => $lastPokemon->getNombre(),
            'peso' => $lastPokemon->getPeso(),
            'altura' => $lastPokemon->getAltura(),
            'ataque' => $lastPokemon->getAtaque(),
            'defensa' => $lastPokemon->getDefensa(),
            'velocidad' => $lastPokemon->getVelocidad(),
        ];

//        return [
//            "name" => "PokemonGet",
//            "data" => $formattedResponse,
//        ];


//        $pokemonList = $repository->findAll();
//        $formattedResponse = array_map(function (PokemonBase $pokemon) {
//            return [
//                'numeroPokedex' => $pokemon->getNumeroPokedex(),
//                'nombre' => $pokemon->getNombre(),
//                'peso' => $pokemon->getPeso(),
//                'altura' => $pokemon->getAltura(),
//                'ataque' => $pokemon->getAtaque(),
//                'defensa' => $pokemon->getDefensa(),
//                'velocidad' => $pokemon->getVelocidad(),
//            ];
//        }, $pokemonList);
//
//        return [
//            "name" => "PokemonGet",
//            "data" => $formattedResponse,
//        ];
    }
}


