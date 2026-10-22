--TEST--
Translated seed (inferredbugs-java: commons-csv/64)
--FILE--
<?php

function randStr() {
    $sz = rand(0, 20);
    $buf = '';
    for ($i = 0; $i < $sz; $i++) {
        $what = rand(0, 19);
        switch ($what) {
            case 0: $ch = "\r"; break;
            case 1: $ch = "\n"; break;
            case 2: $ch = "\t"; break;
            case 3: $ch = "\f"; break;
            case 4: $ch = ' '; break;
            case 5: $ch = ','; break;
            case 6: $ch = '"'; break;
            case 7: $ch = '\''; break;
            case 8: $ch = '\\'; break;
            default: $ch = random_int(0, 299); break;
        }
        $buf .= $ch;
    }
    return $buf;
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

    $result = '';
    foreach ($lines as $line) {
        $result .= implode(',', array_map(function($value) {
            return '"' . str_replace('"', '""', $value) . '"';
        }, $line)) . "\n";
    }
    
    $parseResult = array_map(function($line) {
        return str_getcsv($line);
    }, explode("\n", trim($result)));
}

$iter = 10000;
for ($i = 0; $i < $iter; $i++) {
    doOneRandom();
}
--EXPECT--
