--TEST--
Translated seed (inferredbugs-java: cloud-slang/33)
--FILE--
<?php

$input = "sensitive_value";
$inputArray = array($input);
$outputArray = array();

foreach ($inputArray as $value) {
    $outputArray[] = $value;
}

$flowDataInputs = $inputArray;
$flowDataOutputs = $outputArray;

$stepDataInputs = $inputArray;
$stepDataOutputs = $outputArray;

$success = true;

if ($success) {
    $flowData = array('inputs' => $flowDataInputs, 'outputs' => $flowDataOutputs);
    $stepData = array('inputs' => $stepDataInputs, 'outputs' => $stepDataOutputs);
}
--EXPECT--
