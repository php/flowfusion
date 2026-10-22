--TEST--
Translated seed (inferredbugs-java: commons-csv/61)
--FILE--
<?php

$recordSeparator = "\n";

function randStr() {
    $sz = rand(1, 20);
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
            default: $buf .= chr(rand(0, 255)); break;
        }
    }
    return $buf;
}

function doOneRandom() {
    global $recordSeparator;
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
            return strpos($value, ',') !== false ? '"' . str_replace('"', '""', $value) . '"' : $value;
        }, $line)) . $recordSeparator;
    }

    return $result;
}

for ($i = 0; $i < 10000; $i++) {
    doOneRandom();
}
--EXPECT--
