<?php declare(strict_types=1);

namespace RickAndMorty;

use GuzzleHttp\Client;
use RickAndMorty\Models\Character;
use RickAndMorty\Models\Episode;
use RickAndMorty\Models\Location;

ini_set('max_execution_time', '120');

class ApiGetter
{

    private Client $client;


    public function __construct()
    {
        $this->client = new Client(['verify' => false]);
    }

    public function ensureCacheDirectoryExists(): void
    {
        if (!file_exists('cache')) {
            mkdir('cache', 0777, true);
        }
    }

    public function getCharactersByPage(int $pageNumber): array
    {
        $this->ensureCacheDirectoryExists();
        $cacheFile = "cache/page_$pageNumber.json";
        $currentTime = time();
        if (file_exists($cacheFile) && ($currentTime - filemtime($cacheFile)) < 300) {
            $charactersCollection = json_decode(file_get_contents($cacheFile));
        } else {
            $url = "https://rickandmortyapi.com/api/character?page=$pageNumber";
            $response = $this->client->get($url);
            $charactersCollection = json_decode($response->getBody()->getContents());
            file_put_contents($cacheFile, json_encode($charactersCollection));
        }

        return $this->createCollection($charactersCollection);
    }

    private function createCollection(object $charactersCollection): array
    {
        $collection = [];
        $locations = [];
        foreach ($charactersCollection->results as $character) {
            $characterArray = get_object_vars($character);
            $episode = json_decode($this->client->get($character->episode[0])->getBody()->getContents());
            $location = $characterArray['location']->name;
            if (!in_array($location, $locations)) {
                $locations[] = $location;
            }
            $collection[] = new Character(
                $characterArray['image'],
                $characterArray['name'],
                $characterArray['status'],
                $characterArray['species'],
                $location,
                $episode->name
            );
        }
        return [
            'collection' => $collection,
            'locations' => $locations
        ];
    }

    public function getEpisodesByPage(int $pageNumber, int $pageSize = 20): array
    {
        $this->ensureCacheDirectoryExists();
        $cacheFile = "cache/episodes_$pageNumber.json";
        $currentTime = time();
        if (file_exists($cacheFile) && ($currentTime - filemtime($cacheFile)) < 300) {
            $episodesCollection = json_decode(file_get_contents($cacheFile));
        }   else {
            $url = "https://rickandmortyapi.com/api/episode?page=$pageNumber";
            $response = $this->client->get($url);
            $episodesCollection = json_decode($response->getBody()->getContents());
            file_put_contents($cacheFile, json_encode($episodesCollection));
        }
        return $this->createEpisodeCollection($episodesCollection, $pageSize);
    }

    private function createEpisodeCollection(object $episodesCollection, int $pageSize): array
    {
        $collection = [];
        $episodeIndex = 0;
        foreach ($episodesCollection->results as $episode) {
            if ($episodeIndex >= $pageSize) {
                break;
            }
            $characters = [];
            foreach ($episode->characters as $characterUrl) {
                $characterData = json_decode($this->client->get($characterUrl)->getBody()->getContents());
                $characters[] = $characterData->name;
            }
            $collection[] = new Episode(
                $episode->id,
                $episode->name,
                $episode->air_date,
                $characters
            );
            $episodeIndex++;
        }
        return $collection;
    }

    public function getLocationsByPage(int $pageNumber): array
    {
        $this->ensureCacheDirectoryExists();
        $cacheFile = "cache/location_$pageNumber.json";
        $currentTime = time();
        if (file_exists($cacheFile) && ($currentTime - filemtime($cacheFile)) < 300) {
            $locationsCollection = json_decode(file_get_contents($cacheFile));
        } else {
            $url = "https://rickandmortyapi.com/api/location?page=$pageNumber";
            $response = $this->client->get($url);
            $locationsCollection = json_decode($response->getBody()->getContents());
            file_put_contents($cacheFile, json_encode($locationsCollection));
        }
        return $this->createLocationCollection($locationsCollection);
    }

    private function createLocationCollection(object $locationsCollection): array
    {
        $collection = [];
        foreach ($locationsCollection->results as $location) {
            $characters = [];
            foreach ($location->residents as $residentUrl) {
                $residentData = json_decode($this->client->get($residentUrl)->getBody()->getContents());
                $characters[] = $residentData->name;
            }
            $collection[] = new Location(
                $location->id,
                $location->name,
                $location->type,
                $location->dimension,
                $characters
            );
        }
        return $collection;
    }

    public function getCharacterByName(string $name): ?Character
    {
        $url = "https://rickandmortyapi.com/api/character/?name={$name}";
        $response = $this->client->get($url);
        $charactersCollection = json_decode($response->getBody()->getContents());
        $results = $charactersCollection->results;

        if (empty($results)) {
            return null;
        }

        $characterData = $results[0];
        $episode = json_decode($this->client->get($characterData->episode[0])->getBody()->getContents());

        return new Character(
            $characterData->image,
            $characterData->name,
            $characterData->status,
            $characterData->species,
            $characterData->location->name,
            $episode->name
        );
    }
}
