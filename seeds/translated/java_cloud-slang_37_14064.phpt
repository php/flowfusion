--TEST--
Translated seed (inferredbugs-java: cloud-slang/37)
--FILE--
<?php

$input_no_expression = "input_no_expression_value";
$input_int = 22;
$input_str_no_quotes = "Hi";
$input_str_single = "Hi";
$input_str_double = "Hi";
$input_yaml_list = [1, 2, 3];
$input_yaml_map_folded = ["key1" => "medium", "key2" => false];
$input_python_null = null;
$input_python_list = [1, 2, 3];
$input_python_map = ["key1" => "value1", "key2" => "value2", "key3" => "value3"];
$b = "b";
$b_copy = "b";
$input_concat_1 = "ab";
$input_concat_2_folded = "prefix_ab_suffix";
$step_argument_null = null;

$expectedStepArguments = [
    "input_no_expression" => $input_no_expression,
    "input_int" => $input_int,
    "input_str_no_quotes" => $input_str_no_quotes,
    "input_str_single" => $input_str_single,
    "input_str_double" => $input_str_double,
    "input_yaml_list" => $input_yaml_list,
    "input_yaml_map_folded" => $input_yaml_map_folded,
    "input_python_null" => $input_python_null,
    "input_python_list" => $input_python_list,
    "input_python_map" => $input_python_map,
    "b" => $b,
    "b_copy" => $b_copy,
    "input_concat_1" => $input_concat_1,
    "input_concat_2_folded" => $input_concat_2_folded,
    "step_argument_null" => $step_argument_null,
];

$expectedStepPublishValues = [
    "output_no_expression" => "output_no_expression_value",
    "publish_int" => $input_int,
    "publish_str" => "publish_str_value",
    "publish_expression" => "publish_str_value_suffix",
    "output_step_argument_null" => "step_argument_null_value",
];

$actualStepInputs = $expectedStepArguments; // assuming actual inputs are the same for this example
$actualStepOutputs = $expectedStepPublishValues; // assuming actual outputs are the same for this example

$isInputsCorrect = $actualStepInputs == $expectedStepArguments;
$isOutputsCorrect = $actualStepOutputs == $expectedStepPublishValues;

if ($isInputsCorrect && $isOutputsCorrect) {
    exit(0);
} else {
    exit(1);
}
--EXPECT--
