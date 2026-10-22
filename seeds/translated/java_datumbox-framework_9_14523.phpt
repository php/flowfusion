--TEST--
Translated seed (inferredbugs-java: datumbox-framework/9)
--FILE--
<?php

$data = [
    ['col1' => 10, 'col2' => 5],
    ['col1' => 20, 'col2' => 15],
    ['col1' => null, 'col2' => 10],
    ['col1' => 30, 'col2' => 5],
];

$minColumnValues = [];
$maxColumnValues = [];

function fitX($data, &$minColumnValues, &$maxColumnValues) {
    foreach ($data as $row) {
        foreach ($row as $key => $value) {
            if (is_numeric($value)) {
                if (!isset($minColumnValues[$key])) {
                    $minColumnValues[$key] = $value;
                    $maxColumnValues[$key] = $value;
                } else {
                    $minColumnValues[$key] = min($minColumnValues[$key], $value);
                    $maxColumnValues[$key] = max($maxColumnValues[$key], $value);
                }
            }
        }
    }
}

function normalizeX(&$data, $minColumnValues, $maxColumnValues) {
    foreach ($data as &$row) {
        foreach ($minColumnValues as $column => $min) {
            $max = $maxColumnValues[$column];
            if ($row[$column] === null) {
                continue;
            }
            if ($min === $max) {
                $row[$column] = ($min > 0) ? 1.0 : 0.0;
            } else {
                $row[$column] = ($row[$column] - $min) / ($max - $min);
            }
        }
    }
}

fitX($data, $minColumnValues, $maxColumnValues);
normalizeX($data, $minColumnValues, $maxColumnValues);

foreach ($data as $row) {
    print_r($row);
}
--EXPECT--
