--TEST--
Translated seed (inferredbugs-java: cloud-slang/16)
--FILE--
<?php

$userInputs = [];
$stepsData = [];
$totalSteps = 5; // Simulating steps in a loop
$value = 1;

for ($i = 1; $i <= $totalSteps; $i++) {
    if ($i == 3) {
        continue; // Simulating a break in the loop
    }
    $stepsData["step_$i"] = ["input" => $value++];
}

foreach ($stepsData as $key => $step) {
    if (strpos($key, 'step_') === 0) {
        echo "$key: " . $step['input'] . PHP_EOL;
    }
}
--EXPECT--
