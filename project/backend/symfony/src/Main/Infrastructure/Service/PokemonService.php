<?php
namespace App\Main\Infrastructure\Service;

use App\Main\Domain\Repository\Interfaces\IPokemonService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class PokemonService implements IPokemonService {
    private Client $client;
    private DetailedReportService $reportService;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://pokeapi.co/api/v2/'
        ]);
        $this->reportService = new DetailedReportService();
    }

    /**
     * @throws GuzzleException
     */
    public function getPokemonDetails(int $id): array
    {
        $response = $this->client->get("pokemon/{$id}");
//        $pokemonData = json_decode($response->getBody()->getContents(), true);
//        $this->reportService->generateReport($pokemonData);

        return ['test'=>'wip'];
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
