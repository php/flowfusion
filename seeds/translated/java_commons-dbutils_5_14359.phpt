--TEST--
Translated seed (inferredbugs-java: commons-dbutils/5)
--FILE--
<?php

$rs = [
    ["4", "5", "6"],
    ["7", "8", "9"],
    ["10", "11", "12"],
    ["13", "14", "15"],
];

$iter = new ArrayIterator($rs);
$rowCount = 0;
$row = null;

while ($iter->valid()) {
    $rowCount++;
    $row = $iter->current();
    $iter->next();

    if ($row === null) {
        exit(1);
    }
    if (count($row) !== 3) {
        exit(1);
    }
}

if ($rowCount !== 4) {
    exit(1);
}
if ($row[0] !== "4") {
    exit(1);
}
if ($row[1] !== "5") {
    exit(1);
}
if ($row[2] !== "6") {
    exit(1);
}
--EXPECT--
