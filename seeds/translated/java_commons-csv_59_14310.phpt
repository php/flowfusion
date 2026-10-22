--TEST--
Translated seed (inferredbugs-java: commons-csv/59)
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
            case 7: $ch = "'"; break;
            case 8: $ch = '\\'; break;
            default: $ch = mb_chr(rand(0, 300)); break;
        }
        $buf .= $ch;
    }
    return $buf;
}

function printCSV($lines) {
    $output = '';
    foreach ($lines as $line) {
        $output .= '"' . implode('","', array_map('addQuotes', $line)) . "\"\n";
    }
    return $output;
}

function addQuotes($value) {
    return str_contains($value, ',') ? str_replace('"', '""', $value) : $value;
}

$lines = [];
$nLines = rand(1, 4);
$nCol = rand(1, 3);
for ($i = 0; $i < $nLines; $i++) {
    $line = [];
    for ($j = 0; $j < $nCol; $j++) {
        $line[] = randStr();
    }
    $lines[] = $line;
}

$result = printCSV($lines);
echo $result;
--EXPECT--
