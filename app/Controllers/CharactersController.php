<?php declare(strict_types=1);

namespace RickAndMorty\Controllers;

use RickAndMorty\Models\CharactersCollectionsGetter;
use RickAndMorty\View;

class CharactersController
{

    private CharactersCollectionsGetter $client;

    public function __construct ()
    {
        $this->client = new CharactersCollectionsGetter();
    }

    public function allCharacters() : View
    {
        $allCharacters = $this->client->getAllCharacters();
        return new View('basic', ['characters' => $allCharacters]);
    }

    public function oneCharacter($numberOfCharacter) : View
    {
        $singleCharacter = $this->client->getSingleCharacter($numberOfCharacter);
        return new View('single', ['character' => $singleCharacter]);
    }

}
