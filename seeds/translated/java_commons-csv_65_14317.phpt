--TEST--
Translated seed (inferredbugs-java: commons-csv/65)
--FILE--
<?php

function randStr() {
    $chars = ["\r", "\n", "\t", "\f", ' ', ',', '"', "'", '\\'];
    for ($i = 0; $i < 12; $i++) {
        $chars[] = chr(rand(0, 300));
    }
    return implode('', array_map(function() use ($chars) { return $chars[array_rand($chars)]; }, range(1, rand(1, 20))));
}

function doRandom($iter) {
    for ($i = 0; $i < $iter; $i++) {
        doOneRandom();
    }
}

function doOneRandom() {
    $nLines = rand(1, 4);
    $nCol = rand(1, 3);
    $lines = [];
    for ($i = 0; $i < $nLines; $i++) {
        $line = [];
        for ($j = 0; $j < $nCol; $j++) {
            $line[] = randStr();
        }
        $lines[] = $line;
    }

    $sw = '';
    foreach ($lines as $line) {
        $sw .= implode(',', array_map(function($value) {
            return strpos($value, ',') !== false ? '"' . str_replace('"', '""', $value) . '"' : $value;
        }, $line)) . PHP_EOL;
    }

    $result = $sw;
    echo $result; // Output the result instead of comparing
}

doRandom(10000);
--EXPECT--
