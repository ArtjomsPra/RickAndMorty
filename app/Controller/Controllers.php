<?php declare(strict_types=1);

namespace RickAndMorty\Controllers;

use RickAndMorty\ApiGetter;
use RickAndMorty\View;

class Controllers
{

    private ApiGetter $client;

    public function __construct()
    {
        $this->client = new ApiGetter();
    }

    public function getCharactersByPage(): View
    {
        $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $allCharacters = $this->client->getCharactersByPage($pageNumber);
        $currentPage = $pageNumber;
        $hasNextPage = !empty($allCharacters);
        return new View('characters', [
            'characters' => $allCharacters,
            'currentPage' => $currentPage,
            'hasNextPage' => $hasNextPage
        ]);
    }

    public function getEpisodesByPage(): View
    {
        $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $allEpisodes = $this->client->getEpisodesByPage($pageNumber);
        $currentPage = $pageNumber;
        $hasNextPage = !empty($allEpisodes);
        return new View('episodes', [
            'episodes' => $allEpisodes,
            'currentPage' => $currentPage,
            'hasNextPage' => $hasNextPage
        ]);
    }

    public function getLocationsByPage(): View
    {
        $pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $allLocations = $this->client->getLocationsByPage($pageNumber);
        $currentPage = $pageNumber;
        $hasNextPage = !empty($allEpisodes);
        return new View('locations', [
            'locations' => $allLocations,
            'currentPage' => $currentPage,
            'hasNextPage' => $hasNextPage
        ]);
    }

    public function searchCharacter(): View
    {
        $name = isset($_GET['name']) ? trim($_GET['name']) : '';

                $character = $this->client->getCharacterByName($name);

                return new View('character', [
            'character' => $character
        ]);
    }

}
