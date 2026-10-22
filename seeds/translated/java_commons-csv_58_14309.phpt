--TEST--
Translated seed (inferredbugs-java: commons-csv/58)
--FILE--
<?php

function randStr() {
    $length = rand(1, 20);
    $str = '';
    for ($i = 0; $i < $length; $i++) {
        $what = rand(0, 19);
        if ($what == 0) $str .= "\r";
        elseif ($what == 1) $str .= "\n";
        elseif ($what == 2) $str .= "\t";
        elseif ($what == 3) $str .= "\f";
        elseif ($what == 4) $str .= ' ';
        elseif ($what == 5) $str .= ',';
        elseif ($what == 6) $str .= '"';
        elseif ($what == 7) $str .= '\'';
        elseif ($what == 8) $str .= '\\';
        else $str .= chr(rand(0, 255));
    }
    return $str;
}

function printCsv($data) {
    foreach ($data as $line) {
        echo implode(',', array_map(function($value) {
            return '"' . str_replace('"', '""', $value) . '"';
        }, $line)) . "\n";
    }
}

$linesCount = rand(1, 4);
$columnsCount = rand(1, 3);
$lines = [];
for ($i = 0; $i < $linesCount; $i++) {
    $line = [];
    for ($j = 0; $j < $columnsCount; $j++) {
        $line[] = randStr();
    }
    $lines[] = $line;
}

printCsv($lines);
--EXPECT--
