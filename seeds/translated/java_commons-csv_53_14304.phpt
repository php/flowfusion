--TEST--
Translated seed (inferredbugs-java: commons-csv/53)
--FILE--
<?php

function randStr() {
    $sz = rand(0, 20);
    $buf = '';
    for ($i = 0; $i < $sz; $i++) {
        $what = rand(0, 20);
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

    $output = '';
    foreach ($lines as $line) {
        $output .= implode(',', array_map(function($val) {
            return '"' . str_replace('"', '""', $val) . '"';
        }, $line)) . "\n";
    }

    $parseResult = explode("\n", trim($output));
    foreach ($parseResult as $record) {
        echo $record . "\n";
    }
}

for ($i = 0; $i < 10000; $i++) {
    doOneRandom();
}
--EXPECT--
