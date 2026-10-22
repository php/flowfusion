--TEST--
Translated seed (inferredbugs-java: cloud-slang/18)
--FILE--
<?php

$loopCount = 0;
$maxLoops = 10;

while ($loopCount < $maxLoops) {
    $loopCount++;
}

$inputs = ['input1' => 'value1', 'input2' => 'value2'];
$results = [];
foreach ($inputs as $key => $value) {
    $results[$key] = strtoupper($value);
}

$publishValues = [];
foreach ($results as $key => $value) {
    $publishValues[$key] = $value;
}

$nextStep = null;
$executableResult = 'success';
$taskNavigation = [
    'success' => ['nextStepId' => 1],
    'failure' => ['nextStepId' => null]
];

if (isset($taskNavigation[$executableResult])) {
    $nextStep = $taskNavigation[$executableResult]['nextStepId'];
}

if ($nextStep === null) {
    // end the flow
    exit;
}

exit;
--EXPECT--
