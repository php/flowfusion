--TEST--
Translated seed (inferredbugs-csharp: parquet-dotnet/2)
--FILE--
<?php
$values = [1, 2, 3, 4, 5, 6];
$dictionary = ['a', 'b', 'c', 'd'];
$indexes = [3, 1, 0, 2, 1];
$definitions = [1, 0, 1, 1, 0, 1, 1];
$repetitions = [0, 0, 1, 1, 0, 0, 2, 2];

$maxValues = 4;

if ($dictionary === null && $definitions === null && $indexes === null && $repetitions === null) {
    exit;
}

if ($dictionary !== null && $indexes !== null) {
    if (count($indexes) > $maxValues) {
        $indexes = array_slice($indexes, 0, $maxValues);
    }
    $tmp = [];
    foreach ($indexes as $i) {
        $tmp[] = $dictionary[$i];
    }
    if (count($tmp) > $maxValues) {
        $tmp = array_slice($tmp, 0, $maxValues);
    }
    foreach ($tmp as $el) {
        $values[] = $el;
    }
}

if ($definitions !== null) {
    if (count($definitions) > $maxValues) {
        $definitions = array_slice($definitions, 0, $maxValues);
    }
    $merged = [];
    $valueIdx = 0;
    foreach ($definitions as $isDefinedInt) {
        $merged[] = $isDefinedInt != 0 ? $values[$valueIdx++] : null;
    }
    $values = $merged;
}

if ($repetitions !== null) {
    $chunks = [];
    $chunk = [];
    $rep = PHP_INT_MAX;
    for ($i = 0; $i < count($values); $i++) {
        $cr = $repetitions[$i];
        if ($cr < $rep) {
            if (count($chunk) > 0) {
                $chunks[] = $chunk;
            }
            $chunk = [];
        }
        $rep = $cr;
        $chunk[] = $values[$i];
    }
    if (count($chunk) > 0) {
        $chunks[] = $chunk;
    }
    $values = $chunks;
}

if (count($values) > $maxValues) {
    $values = array_slice($values, 0, $maxValues);
}

if (count($values) > 2) {
    array_shift($values);
    while (count($values) > 2) {
        array_pop($values);
    }
}

echo json_encode($values, JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
