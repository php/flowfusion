--TEST--
Translated seed (inferredbugs-java: commons-csv/110)
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
    $sz = rand(0, 20);
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
            case 7: $buf .= "'"; break;
            case 8: $buf .= "\\"; break;
            default: $buf .= chr(rand(0, 299)); break;
        }
    }
    return $buf;
}

function doRandom($iter) {
    for ($i = 0; $i < $iter; $i++) {
        $nLines = rand(1, 4);
        $nCol = rand(1, 3);
        $lines = [];
        for ($j = 0; $j < $nLines; $j++) {
            $line = [];
            for ($k = 0; $k < $nCol; $k++) {
                $line[] = randStr();
            }
            $lines[] = $line;
        }
        $result = '';
        foreach ($lines as $line) {
            $result .= implode(',', array_map(function($v) {
                return '"' . str_replace('"', '""', $v) . '"';
            }, $line)) . "\n";
        }
        $parseResult = explode("\n", trim($result));
        foreach ($lines as $i => $line) {
            $parsedLine = explode(',', $parseResult[$i]);
            for ($j = 0; $j < count($line); $j++) {
                if ($line[$j] !== trim($parsedLine[$j], '"')) {
                    break;
                }
            }
        }
    }
}

$iter = 10000;
doRandom($iter);

?>
--EXPECT--
