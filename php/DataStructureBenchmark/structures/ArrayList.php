<?php

declare(strict_types=1);

class ArrayList
{
    private array $items = [];

    public function insert(string $key, string $keyString, string $value): void
    {
        $this->items[] = [
            'key' => $key,
            'keyString' => $keyString,
            'value' => $value,
        ];
    }

    public function search(string $key): ?array
    {
        foreach ($this->items as $item) {
            if ($item['key'] === $key) {
                return $item;
            }
        }
        return null;
    }

    public function sortByKeyString(): void
    {
        usort($this->items, static function (array $a, array $b): int {
            return strcmp($a['keyString'], $b['keyString']);
        });
    }

    public function count(): int
    {
        return count($this->items);
    }
}