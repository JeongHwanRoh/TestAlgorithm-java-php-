<?php

declare(strict_types=1);

class Graph
{
    private array $vertices = []; // key => ['keyString'=>..., 'value'=>...]
    private array $adjList = [];  // key => [neighborKey1, neighborKey2, ...]
    private array $lastByPrefix = [];

    public function insert(string $key, string $keyString, string $value): void
    {
        $this->vertices[$key] = [
            'key' => $key,
            'keyString' => $keyString,
            'value' => $value,
        ];

        if (!isset($this->adjList[$key])) {
            $this->adjList[$key] = [];
        }

        $prefix = explode('-', $keyString)[0] ?? 'default';

        if (isset($this->lastByPrefix[$prefix])) {
            $prevKey = $this->lastByPrefix[$prefix];
            $this->adjList[$prevKey][] = $key;
            $this->adjList[$key][] = $prevKey;
        }

        $this->lastByPrefix[$prefix] = $key;
    }

    public function search(string $key): ?array
    {
        return $this->vertices[$key] ?? null;
    }

    public function sortByKeyString(): void
    {
        uasort($this->vertices, static function (array $a, array $b): int {
            return strcmp($a['keyString'], $b['keyString']);
        });
    }

    public function count(): int
    {
        return count($this->vertices);
    }
}