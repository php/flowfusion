--TEST--
Translated seed (inferredbugs-java: Metanome/22)
--FILE--
<?php

$executionIdentifier = 'test_execution';
$algorithmId = 1;
$inputsJson = json_encode(['input1', 'input2']);
$parameterValuesJson = json_encode(['param1' => 'value1', 'param2' => 'value2']);
$executionSetting = json_encode(['parameters' => $parameterValuesJson, 'inputs' => $inputsJson, 'identifier' => $executionIdentifier]);

try {
    $process = popen("php -r 'sleep(5); echo \"Process completed\";'", 'r');
    if (is_resource($process)) {
        while (!feof($process)) {
            echo fgets($process);
        }
        pclose($process);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

$execution = ['algorithmId' => $algorithmId, 'executionSetting' => $executionSetting, 'aborted' => false];

echo json_encode($execution);
--EXPECT--
