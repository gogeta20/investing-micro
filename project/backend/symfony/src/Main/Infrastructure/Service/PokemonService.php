<?php
namespace App\Main\Infrastructure\Service;

use App\Main\Domain\Repository\Interfaces\IPokemonService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class PokemonService implements IPokemonService {
    private Client $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://pokeapi.co/api/v2/'
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function getPokemonDetails(string $name): array
    {
        $response = $this->client->get("pokemon/{$name}");
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function getPokemonSpecies(string $name): array
    {
        $response = $this->client->get("pokemon-species/{$name}");
        return json_decode($response->getBody()->getContents(), true);
    }
}
