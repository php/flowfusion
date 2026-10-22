--TEST--
Translated seed (inferredbugs-java: cloud-slang/20)
--FILE--
<?php

function prepareAndRun($compilationArtifact) {
    return [
        'EXEC_START_PATH' => ['inputs' => [], 'outputs' => []],
        'FIRST_STEP_PATH' => ['inputs' => ['input_no_expression' => 'input_no_expression_value', 'input_int' => 22, 'input_str_no_quotes' => 'Hi', 'input_yaml_list' => [1, 2, 3]], 'outputs' => []],
        'SECOND_STEP_KEY' => ['inputs' => ['input_no_expression' => 'input_no_expression_value', 'input_int' => 22], 'outputs' => []]
    ];
}

$resource = 'path/to/values_flow.sl'; 
$operation1 = 'path/to/values_op.sl'; 
$operation2 = 'path/to/noop.sl'; 

$compilationArtifact = true; // Simulating compilation 

$steps = prepareAndRun($compilationArtifact);

$flowData = $steps['EXEC_START_PATH'];
$taskData = $steps['FIRST_STEP_PATH'];
$oneLinerTaskData = $steps['SECOND_STEP_KEY'];

$expectedTaskArguments = [
    'input_no_expression' => 'input_no_expression_value',
    'input_int' => 22,
    'input_str_no_quotes' => 'Hi',
    'input_yaml_list' => [1, 2, 3]
];

$expectedTaskPublishValues = [
    'output_no_expression' => 'output_no_expression_value',
    'publish_int' => 22,
    'publish_str' => 'publish_str_value',
    'publish_expression' => 'publish_str_value_suffix'
];

$expectedOneLinerTaskArguments = [
    'input_no_expression' => 'input_no_expression_value',
    'input_int' => 22
];

function includeAllPairs($data, $expected) {
    foreach ($expected as $key => $value) {
        if (!array_key_exists($key, $data) || $data[$key] !== $value) {
            return false;
        }
    }
    return true;
}

includeAllPairs($taskData['inputs'], $expectedTaskArguments);
includeAllPairs($taskData['outputs'], $expectedTaskPublishValues);
includeAllPairs($oneLinerTaskData['inputs'], $expectedOneLinerTaskArguments);
--EXPECT--
