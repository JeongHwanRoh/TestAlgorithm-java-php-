<?php

declare(strict_types=1);

require_once __DIR__ . '/structures/BTree.php';
require_once __DIR__ . '/structures/HashTable.php';
require_once __DIR__ . '/structures/LinkedList.php';
require_once __DIR__ . '/structures/ArrayList.php';
require_once __DIR__ . '/structures/Vector.php';
require_once __DIR__ . '/structures/Graph.php';
require_once __DIR__ . '/structures/Heap.php';

const DATA_FILE = __DIR__ . '/data/dataset.txt';
const RESULT_DIR = __DIR__ . '/results';
const RESULT_FILE = RESULT_DIR . '/benchmark_result.txt';

// 검색 샘플 개수
const SEARCH_SAMPLE_SIZE = 1000;

// 정렬 실행 여부
const ENABLE_SORT = true;

function ensureDirectory(string $dir): void
{
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

function nowMs(): float
{
    return microtime(true) * 1000;
}

/**
 * 검색용 key (UUID) 샘플 로드
 */
function loadSearchKeys(string $file, int $sampleSize = SEARCH_SAMPLE_SIZE): array
{
    $keys = [];
    $fp = fopen($file, 'rb');

    if ($fp === false) {
        throw new RuntimeException('데이터 파일을 열 수 없습니다.');
    }

    // 헤더 스킵
    fgets($fp);

    while (($line = fgets($fp)) !== false && count($keys) < $sampleSize) {
        $parts = str_getcsv(trim($line), ',', '"', '\\');

        if (count($parts) >= 1) {
            $keys[] = $parts[0]; // UUID
        }
    }

    fclose($fp);
    return $keys;
}

/**
 * 각 자료구조 벤치마크 실행
 */
function benchmarkStructure(string $name, object $structure, string $dataFile, array $searchKeys): string
{
    // ------------------
    // INSERT
    // ------------------
    $insertStart = nowMs();

    $fp = fopen($dataFile, 'rb');

    if ($fp === false) {
        throw new RuntimeException('데이터 파일을 열 수 없습니다.');
    }

    // 헤더 스킵
    fgets($fp);

    $count = 0;

    while (($line = fgets($fp)) !== false) {

        $parts = str_getcsv(trim($line), ',', '"', '\\');

        // ⭐ 3컬럼 처리
        if (count($parts) !== 3) {
            continue;
        }

        [$key, $keyString, $value] = $parts;

        $structure->insert($key, $keyString, $value);

        $count++;
    }

    fclose($fp);

    $insertEnd = nowMs();
    $insertTime = $insertEnd - $insertStart;

    // ------------------
    // SEARCH
    // ------------------
    $searchStart = nowMs();

    $found = 0;

    foreach ($searchKeys as $key) {
        $result = $structure->search($key);

        if ($result !== null) {
            $found++;
        }
    }

    $searchEnd = nowMs();
    $searchTime = $searchEnd - $searchStart;

    // ------------------
    // SORT
    // ------------------
    $sortTime = 0.0;

    if (ENABLE_SORT && method_exists($structure, 'sortByKeyString') && $name !== 'BTree') {
        $sortStart = nowMs();

        $structure->sortByKeyString();

        $sortEnd = nowMs();
        $sortTime = $sortEnd - $sortStart;
    }

    // ------------------
    // MEMORY
    // ------------------
    $peakMemory = memory_get_peak_usage(true) / 1024 / 1024;

    // ------------------
    // RESULT
    // ------------------
    $result = [];
    $result[] = "========== {$name} ==========";
    $result[] = "Inserted Count     : {$count}";
    $result[] = "Insert Time (ms)   : " . number_format($insertTime, 3);
    $result[] = "Search Sample      : " . count($searchKeys);
    $result[] = "Found Count        : {$found}";
    $result[] = "Search Time (ms)   : " . number_format($searchTime, 3);
    $result[] = "Sort Time (ms)     : " . number_format($sortTime, 3);
    $result[] = "Peak Memory (MB)   : " . number_format($peakMemory, 3);
    $result[] = "";

    return implode(PHP_EOL, $result);
}

// ------------------
// 실행
// ------------------

if (!file_exists(DATA_FILE)) {
    exit("data/dataset.txt 가 없습니다. 먼저 generate_data.php를 실행하세요." . PHP_EOL);
}

ensureDirectory(RESULT_DIR);

// 검색용 key 샘플
$searchKeys = loadSearchKeys(DATA_FILE);

// 자료구조 목록
$structures = [
    'HashTable'  => new HashTable(),
    'BTree'      => new BTree(8),
    'LinkedList' => new LinkedList(),
    'ArrayList'  => new ArrayList(),
    'Vector'     => new Vector(),
    'Graph'      => new Graph(),
    'Heap'       => new HeapStructure(),
];

$output = [];
$output[] = "Benchmark Started At: " . date('Y-m-d H:i:s');
$output[] = "Data File: " . DATA_FILE;
$output[] = "Search Sample Size: " . count($searchKeys);
$output[] = "";

foreach ($structures as $name => $structure) {

    gc_collect_cycles();

    $output[] = benchmarkStructure($name, $structure, DATA_FILE, $searchKeys);

    unset($structure);

    gc_collect_cycles();
}

file_put_contents(RESULT_FILE, implode(PHP_EOL, $output));

echo "벤치마크 완료: " . RESULT_FILE . PHP_EOL;