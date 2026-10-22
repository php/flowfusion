--TEST--
Translated seed (inferredbugs-java: commons-csv/68)
--FILE--
<?php

function randStr() {
    $r = rand(0, 19);
    $sz = rand(0, 20);
    $buf = '';
    for ($i = 0; $i < $sz; $i++) {
        switch ($r) {
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

        $output = '';
        foreach ($lines as $line) {
            $output .= '"' . implode('","', $line) . '"' . PHP_EOL;
        }

        $result = $output; // Simulated output
    }
}

doRandom(10000);
--EXPECT--
