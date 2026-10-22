--TEST--
Translated seed (inferredbugs-java: basex/1)
--FILE--
<?php

$COMPW = 150;
$PATSUB = '[strpos(?, ?) !== false]';
$PATNUM = '[? >= ? and ? <= ?]';
$PATSIMPLE = '[?]';

$keys = ['key1', 'key2', 'key3', 'key4'];

function addInput($pos) {
    global $COMPW;
    echo "Input field added at position $pos with width $COMPW\n";
}

function addKeys($pos) {
    global $keys;
    echo "Keys added at position $pos: " . implode(", ", $keys) . "\n";
}

function query($force) {
    global $keys;
    $first = true;
    $queryParts = [];
    
    for ($c = 0; $c < count($keys); $c++) {
        $key = $keys[$c];
        if ($c % 2 == 0) {
            $val1 = "value1";
            $val2 = "value2";
            $pattern = $PATSUB;

            if ($val1 !== "") {
                $queryParts[] = str_replace(['?', '?', '?'], [$pattern, $key, $val1], "key: ? with pattern ? and value ?");
            }
        }
    }

    $qu = implode(" | ", $queryParts);
    if ($qu === "") {
        $qu = ".";
    }

    echo "Final query: $qu\n";
}

$filter = true;
$exec = true;

if ($filter) {
    echo "Filter enabled\n";
}
if ($exec) {
    query(false);
}

$pos = 0;
addInput($pos);
addKeys($pos + 1);
--EXPECT--
