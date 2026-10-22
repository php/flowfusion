--TEST--
Translated seed (inferredbugs-java: commons-csv/62)
--FILE--
<?php

function randStr() {
    $r = rand(0, 20);
    $buf = '';
    for ($i = 0; $i < $r; $i++) {
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
        return str_contains($value, ',') ? '"' . str_replace('"', '""', $value) . '"' : $value;
    }, $line)) . "\n";
}

$result = $sw;
// Simulating CSV parsing by just outputting the result
echo $result;
--EXPECT--
