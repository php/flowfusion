--TEST--
Translated seed (inferredbugs-java: cloud-slang/23)
--FILE--
<?php

$stepsData = [];
$userInputs = [];
$operation1 = 'operation_that_goes_to_custom_when_value_is_2';
$stepCount = 0;

for ($i = 1; $i <= 3; $i++) {
    if ($i == 2) {
        break;
    }
    $stepsData["step_$i"] = ["inputs" => ["value" => $i]];
    $stepCount++;
}

$stepsData["step_3"] = ["name" => $operation1];
$resultCount = count($stepsData);

$personMap = [
    "john" => 1,
    "jane" => 2,
    "peter" => "three"
];

$userInputs["person_map"] = $personMap;

$stepsDataWithMap = [];
foreach ($userInputs["person_map"] as $name => $value) {
    $stepsDataWithMap[] = ["inputs" => ["text" => $name, "text2" => $value]];
}

$expectedCount = 2;
if ($resultCount === $expectedCount) {
    echo "Expected count matched: $resultCount\n";
}

foreach ($stepsDataWithMap as $index => $data) {
    echo "Step " . ($index + 1) . ": " . $data["inputs"]["text"] . " - " . $data["inputs"]["text2"] . "\n";
}
--EXPECT--
