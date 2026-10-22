--TEST--
Translated seed (inferredbugs-csharp: parquet-dotnet/3)
--FILE--
<?php
$dictionary = [10, 20, 30, 40, 50];
$indexes = [4, 0, 3, 1, 2, 1, 0];
$definitions = [1, 0, 1, 1, 0, 1];
$repetitions = [0, 1, 1, 0, 2, 0];
$values = [];

$maxValues = 5;

if (!($dictionary === null && $definitions === null && $indexes === null && $repetitions === null)) {
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
        $out = [];
        $valueIdx = 0;
        foreach ($definitions as $isDefinedInt) {
            if ($isDefinedInt != 0) {
                $out[] = $values[$valueIdx++];
            } else {
                $out[] = null;
            }
        }
        $values = $out;
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
}

echo json_encode($values, JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
