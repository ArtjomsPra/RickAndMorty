<?php declare(strict_types=1);

namespace RickAndMorty\Controllers;

use RickAndMorty\CharactersCollectionsGetter;
use RickAndMorty\View;

class CharactersController
{

    private CharactersCollectionsGetter $client;

    public function __construct ()
    {
        $this->client = new CharactersCollectionsGetter();
    }
    public function getCharactersByPage() : View
    {
        $pageNumber = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $allCharacters = $this->client->getCharactersByPage($pageNumber);
        $currentPage = $pageNumber;
        $hasNextPage = !empty($allCharacters);
        return new View('modified', [
            'characters' => $allCharacters,
            'currentPage' => $currentPage,
            'hasNextPage' => $hasNextPage
        ]);
    }

}
