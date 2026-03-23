<?php

function uuidV4() {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

// 단어 리스트
$animals = ["tiger", "lion", "wolf", "bear", "eagle", "shark", "fox", "whale", "dragon", "panther"];
$natures = ["river", "forest", "ocean", "mountain", "desert", "sky", "storm", "wind", "fire", "ice"];
$concepts = ["energy", "power", "force", "light", "shadow", "speed", "time", "gravity", "signal", "pulse"];
$cosmos = ["galaxy", "star", "planet", "universe", "orbit", "comet", "nebula", "meteor", "cosmos", "void"];

$fp = fopen("data/dataset.txt", "w");

// 헤더
fwrite($fp, "key,keyString,value\n");

$count = 0;
$target = 200000; // 20만

for ($i = 0; $i < $target; $i++) {

    $uuid = uuidV4();

    $a = $animals[array_rand($animals)];
    $b = $natures[array_rand($natures)];
    $c = $concepts[array_rand($concepts)];
    $d = $cosmos[array_rand($cosmos)];

    $keyString = "{$a}-{$b}-{$c}-{$d}";

    $value = "https://example.com/{$a}/{$b}/{$c}/{$d}/" . bin2hex(random_bytes(8));

    fwrite($fp, "{$uuid},{$keyString},{$value}\n");

    // 진행 로그 (optional)
    if ($i % 100000 === 0) {
        echo "Generated: {$i}\n";
    }
}

fclose($fp);

echo "총 {$target}개 생성 완료\n";