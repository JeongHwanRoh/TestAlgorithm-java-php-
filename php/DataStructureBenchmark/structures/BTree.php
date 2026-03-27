<?php

declare(strict_types=1);

class BTreeNode
{
    public bool $leaf;
    public array $keys = [];      // 각 원소: ['key'=>..., 'keyString'=>..., 'value'=>...]
    public array $children = [];

    public function __construct(bool $leaf = true)
    {
        $this->leaf = $leaf;
    }
}

class BTree
{
    private BTreeNode $root;
    private int $t; // minimum degree

    public function __construct(int $t = 3)
    {
        $this->t = $t;
        $this->root = new BTreeNode(true);
    }

    public function insert(string $key, string $keyString, string $value): void
    {
        $record = [
            'key' => $key,
            'keyString' => $keyString,
            'value' => $value,
        ];

        $root = $this->root;

        if (count($root->keys) === 2 * $this->t - 1) {
            $newRoot = new BTreeNode(false);
            $newRoot->children[] = $root;
            $this->splitChild($newRoot, 0);
            $this->root = $newRoot;
            $this->insertNonFull($newRoot, $record);
        } else {
            $this->insertNonFull($root, $record);
        }
    }

    public function search(string $key): ?array
    {
        return $this->searchNode($this->root, $key);
    }

    public function sortByKeyString(): array
    {
        $result = [];
        $this->traverse($this->root, $result);

        usort($result, static function (array $a, array $b): int {
            return strcmp($a['keyString'], $b['keyString']);
        });

        return $result;
    }

    private function searchNode(BTreeNode $node, string $key): ?array
    {
        $current = $node;
        while (true) {
            $i = 0;
            $cnt = count($current->keys);
            while ($i < $cnt && strcmp($key, $current->keys[$i]['key']) > 0) {
                $i++;
            }
            if ($i < $cnt && $current->keys[$i]['key'] === $key) {
                return $current->keys[$i];
            }
            if ($current->leaf) {
                return null;
            }
            $current = $current->children[$i];
        }
    }

    private function splitChild(BTreeNode $parent, int $index): void
    {
        $t = $this->t;
        $fullChild = $parent->children[$index];
        $newNode = new BTreeNode($fullChild->leaf);

        $middleKey = $fullChild->keys[$t - 1];

        $newNode->keys = array_slice($fullChild->keys, $t);
        $fullChild->keys = array_slice($fullChild->keys, 0, $t - 1);

        if (!$fullChild->leaf) {
            $newNode->children = array_slice($fullChild->children, $t);
            $fullChild->children = array_slice($fullChild->children, 0, $t);
        }

        array_splice($parent->children, $index + 1, 0, [$newNode]);
        array_splice($parent->keys, $index, 0, [$middleKey]);
    }

    private function insertNonFull(BTreeNode $node, array $record): void
    {
        $current = $node;
        while (true) {
            $i = count($current->keys) - 1;

            if ($current->leaf) {
                $current->keys[] = $record;
                while ($i >= 0 && strcmp($record['key'], $current->keys[$i]['key']) < 0) {
                    $current->keys[$i + 1] = $current->keys[$i];
                    $i--;
                }
                $current->keys[$i + 1] = $record;
                return;
            }

            while ($i >= 0 && strcmp($record['key'], $current->keys[$i]['key']) < 0) {
                $i--;
            }
            $i++;

            if (count($current->children[$i]->keys) === 2 * $this->t - 1) {
                $this->splitChild($current, $i);
                if (strcmp($record['key'], $current->keys[$i]['key']) > 0) {
                    $i++;
                }
            }

            $current = $current->children[$i];
        }
    }

    private function traverse(BTreeNode $node, array &$result): void
    {
        // BFS로 전체 키 수집 (sortByKeyString에서 어차피 usort하므로 순서 무관)
        $queue = [$node];
        while (!empty($queue)) {
            $current = array_shift($queue);
            foreach ($current->keys as $key) {
                $result[] = $key;
            }
            if (!$current->leaf) {
                foreach ($current->children as $child) {
                    $queue[] = $child;
                }
            }
        }
    }
}