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
        $i = 0;
        while ($i < count($node->keys) && strcmp($key, $node->keys[$i]['key']) > 0) {
            $i++;
        }

        if ($i < count($node->keys) && $node->keys[$i]['key'] === $key) {
            return $node->keys[$i];
        }

        if ($node->leaf) {
            return null;
        }

        return $this->searchNode($node->children[$i], $key);
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
        $i = count($node->keys) - 1;

        if ($node->leaf) {
            $node->keys[] = $record;
            while ($i >= 0 && strcmp($record['key'], $node->keys[$i]['key']) < 0) {
                $node->keys[$i + 1] = $node->keys[$i];
                $i--;
            }
            $node->keys[$i + 1] = $record;
            return;
        }

        while ($i >= 0 && strcmp($record['key'], $node->keys[$i]['key']) < 0) {
            $i--;
        }

        $i++;

        if (count($node->children[$i]->keys) === 2 * $this->t - 1) {
            $this->splitChild($node, $i);

            if (strcmp($record['key'], $node->keys[$i]['key']) > 0) {
                $i++;
            }
        }

        $this->insertNonFull($node->children[$i], $record);
    }

    private function traverse(BTreeNode $node, array &$result): void
    {
        $n = count($node->keys);

        for ($i = 0; $i < $n; $i++) {
            if (!$node->leaf) {
                $this->traverse($node->children[$i], $result);
            }
            $result[] = $node->keys[$i];
        }

        if (!$node->leaf) {
            $this->traverse($node->children[$n], $result);
        }
    }
}