--TEST--
Translated seed (inferredbugs-java: commons-dbutils/8)
--FILE--
<?php
class MapListHandler {
    public function handle($resultSet) {
        $results = [];
        foreach ($resultSet as $row) {
            $results[] = $row;
        }
        return $results;
    }
}

$rows = [
    ["one" => "4", "TWO" => "5", "Three" => "6"],
    ["one" => "7", "TWO" => "8", "Three" => "9"]
];

$emptyResultSet = [];

$handler = new MapListHandler();
$results = $handler->handle($rows);
$rowCount = count($results);

if ($rowCount !== 2) {
    exit(1);
}

foreach ($results as $index => $row) {
    if (count(array_keys($row)) !== 3) {
        exit(1);
    }
}

if ($results[0]["one"] !== "4" || $results[0]["TWO"] !== "5" || $results[0]["Three"] !== "6") {
    exit(1);
}

$emptyResults = $handler->handle($emptyResultSet);
if ($emptyResults !== []) {
    exit(1);
}
?>
--EXPECT--
