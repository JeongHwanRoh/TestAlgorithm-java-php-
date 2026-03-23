<?php

declare(strict_types=1);

class Vector
{
    private SplFixedArray $items;
    private int $size = 0;

    public function __construct(int $capacity = 1024)
    {
        $this->items = new SplFixedArray($capacity);
    }

    public function insert(string $key, string $keyString, string $value): void
    {
        if ($this->size >= $this->items->count()) {
            $this->resize();
        }

        $this->items[$this->size] = [
            'key' => $key,
            'keyString' => $keyString,
            'value' => $value,
        ];
        $this->size++;
    }

    public function search(string $key): ?array
    {
        for ($i = 0; $i < $this->size; $i++) {
            $item = $this->items[$i];
            if ($item['key'] === $key) {
                return $item;
            }
        }
        return null;
    }

    public function sortByKeyString(): void
    {
        $arr = [];
        for ($i = 0; $i < $this->size; $i++) {
            $arr[] = $this->items[$i];
        }

        usort($arr, static function (array $a, array $b): int {
            return strcmp($a['keyString'], $b['keyString']);
        });

        $this->items = new SplFixedArray(max(count($arr), 1));
        $this->size = 0;

        foreach ($arr as $item) {
            $this->insert($item['key'], $item['keyString'], $item['value']);
        }
    }

    public function count(): int
    {
        return $this->size;
    }

    private function resize(): void
    {
        $newCapacity = max(1, $this->items->count() * 2);
        $newItems = new SplFixedArray($newCapacity);

        for ($i = 0; $i < $this->size; $i++) {
            $newItems[$i] = $this->items[$i];
        }

        $this->items = $newItems;
    }
}