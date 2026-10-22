--TEST--
Translated seed (inferredbugs-java: cloud-slang/30)
--FILE--
<?php

$person_map = [
    "john" => 1,
    "jane" => 2,
    "peter" => "three"
];

$stepsData = [];
foreach ($person_map as $name => $value) {
    $stepsData[] = [
        "inputs" => ["text" => $name, "text2" => $value]
    ];
}

$expectedContextSet = [
    ["text" => "john", "text2" => 1],
    ["text" => "jane", "text2" => 2],
    ["text" => "peter", "text2" => "three"]
];

$actualContextSet = [];
foreach ($stepsData as $step) {
    $actualContextSet[] = $step['inputs'];
}

sort($expectedContextSet);
sort($actualContextSet);

if ($expectedContextSet !== $actualContextSet) {
    exit(1);
}

exit(0);
--EXPECT--
