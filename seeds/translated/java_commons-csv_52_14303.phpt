--TEST--
Translated seed (inferredbugs-java: commons-csv/52)
--FILE--
<?php

function randStr() {
    $sz = rand(0, 19);
    $buf = '';
    for ($i = 0; $i < $sz; $i++) {
        $what = rand(0, 19);
        switch ($what) {
            case 0: $buf .= "\r"; break;
            case 1: $buf .= "\n"; break;
            case 2: $buf .= "\t"; break;
            case 3: $buf .= "\f"; break;
            case 4: $buf .= ' '; break;
            case 5: $buf .= ','; break;
            case 6: $buf .= '"'; break;
            case 7: $buf .= '\''; break;
            case 8: $buf .= '\\'; break;
            default: $buf .= chr(rand(0, 299)); break;
        }
    }
    return $buf;
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
        $sw .= implode(',', array_map(function($item) {
            return strpos($item, ',') !== false ? '"' . str_replace('"', '""', $item) . '"' : $item;
        }, $line)) . "\n";
    }
    
    $result = $sw;

    $parseResult = explode("\n", trim($result));
    foreach ($parseResult as $index => $parseLine) {
        $parseResult[$index] = str_getcsv($parseLine);
    }
}

$iter = 10000;
doRandom($iter);
--EXPECT--
