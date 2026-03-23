<?php

declare(strict_types=1);

class LinkedListNode
{
    public string $key;
    public string $keyString;
    public string $value;
    public ?LinkedListNode $next = null;

    public function __construct(string $key, string $keyString, string $value)
    {
        $this->key = $key;
        $this->keyString = $keyString;
        $this->value = $value;
    }
}

class LinkedList
{
    private ?LinkedListNode $head = null;
    private ?LinkedListNode $tail = null;
    private int $count = 0;

    public function insert(string $key, string $keyString, string $value): void
    {
        $node = new LinkedListNode($key, $keyString, $value);

        if ($this->head === null) {
            $this->head = $node;
            $this->tail = $node;
        } else {
            $this->tail->next = $node;
            $this->tail = $node;
        }

        $this->count++;
    }

    public function search(string $key): ?array
    {
        $current = $this->head;
        while ($current !== null) {
            if ($current->key === $key) {
                return [
                    'key' => $current->key,
                    'keyString' => $current->keyString,
                    'value' => $current->value,
                ];
            }
            $current = $current->next;
        }

        return null;
    }

    public function sortByKeyString(): void
    {
        $arr = [];
        $current = $this->head;

        while ($current !== null) {
            $arr[] = [
                'key' => $current->key,
                'keyString' => $current->keyString,
                'value' => $current->value,
            ];
            $current = $current->next;
        }

        usort($arr, static function (array $a, array $b): int {
            return strcmp($a['keyString'], $b['keyString']);
        });

        $this->head = null;
        $this->tail = null;
        $this->count = 0;

        foreach ($arr as $item) {
            $this->insert($item['key'], $item['keyString'], $item['value']);
        }
    }

    public function count(): int
    {
        return $this->count;
    }
}