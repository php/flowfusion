--TEST--
Translated seed (inferredbugs-csharp: dbschemareader/1)
--FILE--
<?php
$cmd = "SELECT 1";
$params = [];
$rows = [
    ["Id" => 1, "Name" => "alpha", "Value" => null],
    ["Id" => 2, "Name" => "beta", "Value" => "x"],
];
$result = [];

foreach ($rows as $row) {
    $record = new stdClass();
    foreach ($row as $k => $v) {
        $record->$k = $v === null ? null : $v;
    }
    $result[] = $record;
}

$output = [
    "InOut" => null,
    "Out" => null,
];

if (array_key_exists("InOut", $output)) {
    $output["InOut"] = $output["InOut"] ?? "default";
}

if (array_key_exists("Out", $output)) {
    $output["Out"] = 42;
}

echo $cmd . PHP_EOL;
echo count($result) . PHP_EOL;
echo ($output["InOut"] === null ? "null" : $output["InOut"]) . PHP_EOL;
echo $output["Out"] . PHP_EOL;
?>
--EXPECT--
