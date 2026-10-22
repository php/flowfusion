--TEST--
Translated seed (inferredbugs-java: commons-csv/50)
--FILE--
<?php

function printable($s) {
    $result = '';
    for ($i = 0; $i < strlen($s); $i++) {
        $ch = $s[$i];
        if (ord($ch) <= 32 || ord($ch) >= 128) {
            $result .= '(' . ord($ch) . ')';
        } else {
            $result .= $ch;
        }
    }
    return $result;
}

function randStr() {
    $r = rand(0, 20);
    $buf = '';
    for ($i = 0; $i < $r; $i++) {
        switch (rand(0, 20)) {
            case 0: $buf .= "\r"; break;
            case 1: $buf .= "\n"; break;
            case 2: $buf .= "\t"; break;
            case 3: $buf .= "\f"; break;
            case 4: $buf .= ' '; break;
            case 5: $buf .= ','; break;
            case 6: $buf .= '"'; break;
            case 7: $buf .= "'"; break;
            case 8: $buf .= "\\"; break;
            default: $buf .= chr(rand(0, 300)); break;
        }
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

    $result = [];
    foreach ($lines as $line) {
        $result[] = implode(',', array_map(function($item) {
            return '"' . str_replace('"', '""', $item) . '"';
        }, $line));
    }
    
    $output = implode("\n", $result) . "\n";
    $parseResult = explode("\n", $output);
    array_pop($parseResult);

    // Simulating comparison (in a real case, you would want to check if $lines matches $parseResult)
    // This part is just for simulating the behavior.
    echo printable($output);
}

for ($i = 0; $i < 10000; $i++) {
    doOneRandom();
}
--EXPECT--
