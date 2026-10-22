--TEST--
Translated seed (inferredbugs-java: commons-csv/69)
--FILE--
<?php

function randStr() {
    $sz = rand(0, 19);
    $buf = "";
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
            default: $ch = chr(rand(0, 255)); break;
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

    $result = "";
    foreach ($lines as $line) {
        $result .= implode(',', array_map(function($item) {
            return '"' . str_replace('"', '""', $item) . '"';
        }, $line)) . "\n";
    }

    return $result;
}

for ($i = 0; $i < 10000; $i++) {
    doOneRandom();
}
--EXPECT--
