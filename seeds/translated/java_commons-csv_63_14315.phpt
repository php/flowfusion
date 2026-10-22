--TEST--
Translated seed (inferredbugs-java: commons-csv/63)
--FILE--
<?php

function printable($s) {
    $result = '';
    for ($i = 0; $i < strlen($s); $i++) {
        $ch = $s[$i];
        if (ord($ch) <= 32 || ord($ch) >= 128) {
            $result .= "(" . ord($ch) . ")";
        } else {
            $result .= $ch;
        }
    }
    return $result;
}

function randStr() {
    $size = rand(0, 20);
    $buf = '';
    for ($i = 0; $i < $size; $i++) {
        $what = rand(0, 19);
        switch ($what) {
            case 0: $buf .= "\r"; break;
            case 1: $buf .= "\n"; break;
            case 2: $buf .= "\t"; break;
            case 3: $buf .= "\f"; break;
            case 4: $buf .= ' '; break;
            case 5: $buf .= ','; break;
            case 6: $buf .= '"'; break;
            case 7: $buf .= "'"; break;
            case 8: $buf .= '\\'; break;
            default: $buf .= chr(rand(0, 255)); break;
        }
    }
    return $buf;
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
        $sw .= implode(',', $line) . "\n";
    }

    $result = $sw;
    $parseResult = explode("\n", trim($result));
    foreach ($parseResult as $i => $line) {
        $parseResult[$i] = str_getcsv($line);
    }

    // Simulating comparison (no assertions)
    $compare = array_map('printable', $lines);
    $resultPrintable = printable($result);
}

$iter = 10000;
for ($i = 0; $i < $iter; $i++) {
    doOneRandom('DEFAULT');
    doOneRandom('EXCEL');
}
--EXPECT--
