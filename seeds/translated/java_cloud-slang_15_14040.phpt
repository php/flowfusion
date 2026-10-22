--TEST--
Translated seed (inferredbugs-java: cloud-slang/15)
--FILE--
<?php

$firstStepPath = 'first_step';
$secondStepKey = 'second_step';
$thirdStepKey = 'third_step';

$stepsData = [];
$loopCount = 3;

for ($i = 1; $i <= $loopCount; $i++) {
    $stepsData["step_$i"] = [
        'inputs' => [
            'value' => $i,
            'name' => "step_$i"
        ]
    ];
}

$firstTask = $stepsData["step_1"];
$secondTask = $stepsData["step_2"];
$thirdTask = $stepsData["step_3"];

var_dump($firstTask['inputs']['value'] === 1);
var_dump($secondTask['inputs']['value'] === 2);
var_dump($thirdTask['inputs']['value'] === 3);
--EXPECT--
