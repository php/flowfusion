--TEST--
Translated seed (inferredbugs-java: commons-dbutils/9)
--FILE--
<?php

$rows = 6;
$cols = 3;
$results = [
    "1" => ["one" => "1", "TWO" => "2", "Three" => "3"],
    "2" => ["one" => "1", "TWO" => "2", "Three" => "3"],
    "3" => ["one" => "1", "TWO" => "2", "Three" => "3"],
    "4" => ["one" => "1", "TWO" => "2", "Three" => "3"],
    "5" => ["one" => "4", "TWO" => "5", "Three" => "6"],
    "6" => ["one" => "4", "TWO" => "5", "Three" => "6"],
];

$resultSet = $results;

if ($resultSet !== null) {
    $resultCount = count($resultSet);
}

foreach ($resultSet as $key => $row) {
    if ($key === null || $row === null || count($row) !== $cols) {
        exit(1);
    }
}

$row = $resultSet["1"];
if ($row["one"] !== "1" || $row["TWO"] !== "2" || $row["Three"] !== "3") {
    exit(1);
}

$row = $resultSet["5"];
if ($row["one"] !== "4" || $row["TWO"] !== "5" || $row["Three"] !== "6") {
    exit(1);
}

$row = $resultSet["6"];
if ($row["one"] !== "4" || $row["TWO"] !== "5" || $row["Three"] !== "6") {
    exit(1);
}

$emptyResultSet = [];
if ($emptyResultSet !== null && count($emptyResultSet) !== 0) {
    exit(1);
}

?>
--EXPECT--
