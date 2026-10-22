--TEST--
Translated seed (inferredbugs-java: cloud-slang/22)
--FILE--
<?php

$person_map = [
    "john" => 1,
    "jane" => 2,
    "peter" => "three"
];

$stepsData = [];
foreach ($person_map as $name => $value) {
    $stepsData[] = ["inputs" => ["text" => $name, "text2" => $value]];
}

$expectedContextSet = [
    ["text" => "john", "text2" => 1],
    ["text" => "jane", "text2" => 2],
    ["text" => "peter", "text2" => "three"]
];

$actualContextSet = array_map(function($step) {
    return $step['inputs'];
}, $stepsData);

$expectedCount = count($expectedContextSet);
$actualCount = count($actualContextSet);

if ($expectedCount !== $actualCount) {
    exit("Counts do not match: expected $expectedCount, got $actualCount");
}

foreach ($expectedContextSet as $expected) {
    if (!in_array($expected, $actualContextSet)) {
        exit("Expected context not found in actual context");
    }
}
--EXPECT--
