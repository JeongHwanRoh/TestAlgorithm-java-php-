<?php

declare(strict_types=1);

class RecordMinHeap extends SplMinHeap
{
    protected function compare($value1, $value2): int
    {
        return strcmp($value2['keyString'], $value1['keyString']);
    }
}

class HeapStructure
{
    private RecordMinHeap $heap;
    private array $index = [];

    public function __construct()
    {
        $this->heap = new RecordMinHeap();
    }

    public function insert(string $key, string $keyString, string $value): void
    {
        $record = [
            'key' => $key,
            'keyString' => $keyString,
            'value' => $value,
        ];

        $this->heap->insert($record);
        $this->index[$key] = $record; // 검색용 보조 인덱스
    }

    public function search(string $key): ?array
    {
        return $this->index[$key] ?? null;
    }

    public function sortByKeyString(): array
    {
        $clone = clone $this->heap;
        $result = [];

        while (!$clone->isEmpty()) {
            $result[] = $clone->extract();
        }

        return $result;
    }

    public function count(): int
    {
        return count($this->index);
    }
}