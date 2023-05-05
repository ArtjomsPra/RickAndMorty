<?php declare(strict_types=1);

namespace RickAndMorty;

use GuzzleHttp\Client;
use RickAndMorty\Model\Character;

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
        $collection[] = new Character
        (
            $character->image,
            $character->name,
            $character->status,
            $character->species,
            $character->location->name,
            json_decode($this->client->get($character->episode[0])->getBody()->getContents())->name
        );
        }
        return $collection;
    }
}
