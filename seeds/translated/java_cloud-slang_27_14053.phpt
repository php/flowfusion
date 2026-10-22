--TEST--
Translated seed (inferredbugs-java: cloud-slang/27)
--FILE--
<?php

$resource = 'path/to/your/yaml/loops/simple_loop.sl';
$operation1 = 'path/to/your/yaml/loops/print.sl';

$compilationArtifact = compile($resource, [$operation1]);

$userInputs = [];
$systemProperties = [
    'loop' => [
        'for.prop1' => 'for_value'
    ]
];

$stepsData = triggerWithData($compilationArtifact, $userInputs, $systemProperties);

$firstTask = $stepsData['first_step_path'];
$secondTask = $stepsData['second_step_key'];
$thirdTask = $stepsData['third_step_key'];

$expectedInputs = [
    'text' => 1,
    'sp_arg' => 'for_value'
];
compareInputs($expectedInputs, $firstTask['inputs']);

$expectedInputs['text'] = 2;
compareInputs($expectedInputs, $secondTask['inputs']);

$expectedInputs['text'] = 3;
compareInputs($expectedInputs, $thirdTask['inputs']);

function compile($resource, $path) {
    // Placeholder for the compilation logic.
    return [];
}

function triggerWithData($compilationArtifact, $userInputs, $systemProperties) {
    // Placeholder for the triggering logic.
    return [];
}

function compareInputs($expected, $actual) {
    if ($expected !== $actual) {
        throw new Exception("Inputs do not match");
    }
}
--EXPECT--
