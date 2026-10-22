--TEST--
Translated seed (inferredbugs-java: commons-csv/51)
--FILE--
<?php

function printable($s) {
    $sb = '';
    for ($i = 0; $i < strlen($s); $i++) {
        $ch = $s[$i];
        if (ord($ch) <= 32 || ord($ch) >= 128) {
            $sb .= '(' . ord($ch) . ')';
        } else {
            $sb .= $ch;
        }
    }
    return $sb;
}

function randStr() {
    $r = rand(0, 19);
    $sz = rand(0, 20);
    $buf = '';
    for ($i = 0; $i < $sz; $i++) {
        switch (rand(0, 20)) {
            case 0: $ch = "\r"; break;
            case 1: $ch = "\n"; break;
            case 2: $ch = "\t"; break;
            case 3: $ch = "\f"; break;
            case 4: $ch = ' '; break;
            case 5: $ch = ','; break;
            case 6: $ch = '"'; break;
            case 7: $ch = '\''; break;
            case 8: $ch = '\\'; break;
            default: $ch = chr(rand(0, 300)); break;
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
    
    $parseResult = explode("\n", trim($result));
    foreach ($parseResult as $record) {
        // no assertion, just processing output
        echo printable($record) . "\n";
    }
}

$iter = 10000;
for ($i = 0; $i < $iter; $i++) {
    doOneRandom();
}
--EXPECT--
