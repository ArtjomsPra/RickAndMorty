<?php declare(strict_types=1);

namespace RickAndMorty;

use GuzzleHttp\Client;
use RickAndMorty\Model\Character;

ini_set('max_execution_time', '120');

class CharactersCollectionsGetter
{

    private Client $client;


    public function __construct()
    {
        $this->client = new Client(['verify' => false]);
    }

    public function getCharactersByPage(int $pageNumber): array
    {
        $url = "https://rickandmortyapi.com/api/character?page=$pageNumber";
        $response = $this->client->get($url);
        $charactersCollection = json_decode($response->getBody()->getContents());
        return $this->createCollection($charactersCollection);

    }

    private function createCollection(object $charactersCollection): array
    {
        $collection = [];
        foreach ($charactersCollection->results as $character) {
            $characterArray = get_object_vars($character);
            $episode = json_decode($this->client->get($character->episode[0])->getBody()->getContents());
            $collection[] = new Character(
                $characterArray['image'],
                $characterArray['name'],
                $characterArray['status'],
                $characterArray['species'],
                $characterArray['location']->name,
                $episode->name
            );
        }
        return $collection;
    }
}
