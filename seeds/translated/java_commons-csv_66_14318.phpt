--TEST--
Translated seed (inferredbugs-java: commons-csv/66)
--FILE--
<?php

function randStr() {
    $r = rand(0, 100);
    $len = rand(1, 20);
    $buf = '';
    for ($i = 0; $i < $len; $i++) {
        $ch = '';
        switch ($r % 20) {
            case 0: $ch = "\r"; break;
            case 1: $ch = "\n"; break;
            case 2: $ch = "\t"; break;
            case 3: $ch = "\f"; break;
            case 4: $ch = ' ';  break;
            case 5: $ch = ',';  break;
            case 6: $ch = '"';  break;
            case 7: $ch = '\''; break;
            case 8: $ch = '\\'; break;
            default: $ch = chr(rand(0, 300)); break;
        }
        $buf .= $ch;
        $r++;
    }
    return $buf;
}

function doRandom($format, $iter) {
    for ($i = 0; $i < $iter; $i++) {
        doOneRandom($format);
    }
}

function doOneRandom($format) {
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
        $sw .= implode(',', array_map(function ($val) use ($format) {
            return '"' . str_replace('"', '""', $val) . '"';
        }, $line)) . "\n";
    }
    $result = rtrim($sw, "\n");

    $parseResult = explode("\n", $result);
    // Errors with parsing or formatting would occur here if there were issues
}

$iter = 10000;
doRandom('DEFAULT', $iter);
doRandom('EXCEL', $iter);
doRandom('MYSQL', $iter);
--EXPECT--
