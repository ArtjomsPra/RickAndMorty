<?php declare(strict_types=1);

namespace RickAndMorty;

class View
{
    private string $template;
    private array $collection;
    public function __construct (string $template, array $collection)
    {
        $this->template = $template;
        $this->collection = $collection;
    }

    public function getCollection(): array
    {
        return $this->collection;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
}
