--TEST--
Translated seed (inferredbugs-java: cloud-slang/19)
--FILE--
<?php

$userInputs = [
    "input_no_expression" => "input_no_expression_value",
    "input_overridable" => "i_should_not_be_assigned"
];

$systemProperties = [
    "user.sys.props.host" => "localhost"
];

$expectedInputs = [
    "input_no_expression" => "input_no_expression_value",
    "input_no_expression_not_required" => null,
    "input_system_property" => "localhost",
    "input_not_overridable" => 25,
    "input_int" => 22,
    "input_str_no_quotes" => "Hi",
    "input_str_single" => "Hi",
    "input_str_double" => "Hi",
    "input_yaml_list" => [1, 2, 3],
    "input_properties_yaml_map_folded" => "medium",
    "input_python_null" => null,
    "input_python_list" => [1, 2, 3],
    "input_python_map" => [
        "key1" => "value1",
        "key2" => "value2",
        "key3" => "value3"
    ],
    "input_python_map_quotes" => [
        "value" => 2
    ],
    "b" => "b",
    "b_copy" => "b",
    "input_concat_1" => "ab",
    "input_concat_2_one_liner" => "prefix_ab_suffix",
    "input_concat_2_folded" => "prefix_ab_suffix",
];

$stepData = [];
$flowData = [];
$taskData = [];

$flowData = array_merge($flowData, $userInputs);
$flowData["input_int"] = 22;

$taskData["outputs"] = [
    "output_no_expression" => "output_no_expression_value",
    "publish_int" => 22,
    "publish_str" => "publish_str_value",
    "publish_expression" => "publish_str_value_suffix"
];

$result = "success";

if ($result === "success") {
    // Flow inputs check
    $inputsCorrect = (count(array_diff_key($expectedInputs, $flowData)) === 0);
    
    // Task outputs check
    $outputsCorrect = ($taskData["outputs"] === [
        "output_no_expression" => "output_no_expression_value",
        "publish_int" => 22,
        "publish_str" => "publish_str_value",
        "publish_expression" => "publish_str_value_suffix"
    ]);

    if ($inputsCorrect && $outputsCorrect) {
        exit(0);
    } else {
        exit(1);
    }
}

exit(1);
--EXPECT--
