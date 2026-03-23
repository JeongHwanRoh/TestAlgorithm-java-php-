<?php

declare(strict_types=1);

class HashTable
{
    private array $table = [];

    public function insert(string $key, string $keyString, string $value): void
    {
        $this->table[$key] = [
            'keyString' => $keyString,
            'value' => $value,
        ];
    }

    public function search(string $key): ?array
    {
        return $this->table[$key] ?? null;
    }

    public function sortByKeyString(): void
    {
        uasort($this->table, static function (array $a, array $b): int {
            return strcmp($a['keyString'], $b['keyString']);
        });
    }

    public function count(): int
    {
        return count($this->table);
    }
}