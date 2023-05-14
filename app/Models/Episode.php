<?php declare(strict_types=1);

namespace RickAndMorty\Models;

class Episode
{
    private int $id;
    private string $name;
    private string $airdate;
    private array $characters;

    public function __construct(
        int    $id,
        string $name,
        string $airdate,
        array  $characters
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->airdate = $airdate;
        $this->characters = $characters;

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAirdate(): string
    {
        return $this->airdate;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }
}
