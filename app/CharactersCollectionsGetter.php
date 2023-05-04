<?php declare(strict_types=1);

namespace RickAndMorty;

use GuzzleHttp\Client;
use RickAndMorty\Models\Character;

class CharactersCollectionsGetter
{

    private Client $client;


    public function __construct()
    {
        $this->client = new Client(['verify' => false]);
    }

    public function getAllCharacters () : array
    {

        $url = 'https://rickandmortyapi.com/api/character';
        $response = $this->client->get($url);
        return $this->createCollection(json_decode($response->getBody()->getContents()));

    }
    private function createCollection (array $charactersCollection): array
    {
        $collection = [];
        foreach ($charactersCollection as $character)
        {
            $firstEpisodeUrl = $character->episode[0];
            $episodeResponse = $this->client->get($firstEpisodeUrl);
            $episode = json_decode($episodeResponse->getBody()->getContents());

        $collection[] = new Character
        (
            $character->image,
            $character->name,
            $character->status,
            $character->species,
            $character->location->name,
            $episode->name
        );
        }
        return $collection;
    }
}
