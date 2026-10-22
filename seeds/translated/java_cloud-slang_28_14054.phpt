--TEST--
Translated seed (inferredbugs-java: cloud-slang/28)
--FILE--
<?php

$stepsData = [];
$personMap = ["john" => 1, "jane" => 2, "peter" => "three"];
$expectedInputs = [];
foreach ($personMap as $name => $value) {
    $expectedInputs[] = ["text" => $name, "text2" => $value];
    $stepsData[] = ["inputs" => ["text" => $name, "text2" => $value]];
}
$actualContextSet = array_map(function($step) { return $step['inputs']; }, $stepsData);
$expectedContextSet = $expectedInputs;

if ($actualContextSet !== $expectedContextSet) {
    exit(1);
}
--EXPECT--
