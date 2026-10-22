--TEST--
Translated seed (inferredbugs-java: cloud-slang/12)
--FILE--
<?php

$testCaseName = "SampleTestCase";
$expectedResult = "Success";
$result = "";
$flowFinished = false;
$errorMessage = "";

function onEvent($eventType, $data) {
    global $flowFinished, $errorMessage, $testCaseName, $result, $expectedResult;

    switch ($eventType) {
        case 'SCORE_FINISHED_EVENT':
            $flowFinished = true;
            break;
        case 'SCORE_ERROR_EVENT':
        case 'SCORE_FAILURE_EVENT':
            $errorMessage = "Failed running test case: " . $testCaseName . ". " . $data['SCORE_ERROR_LOG_MSG'] . " , " . $data['SCORE_ERROR_MSG'];
            $flowFinished = true;
            $result = "FAILURE";
            break;
        case 'SLANG_EXECUTION_EXCEPTION':
            $errorMessage = "Failed running test case: " . $testCaseName . ". " . $data['EXCEPTION'];
            break;
        case 'EVENT_EXECUTION_FINISHED':
            $result = $data['RESULT'];
            printFinishEvent($data);
            break;
    }
}

function printFinishEvent($data) {
    global $expectedResult, $testCaseName;
    $flowResult = $data['RESULT'];
    $flowName = $data['EXECUTABLE_NAME'];
    if ($expectedResult == null || $expectedResult === $flowResult) {
        echo "Passed test case: " . $testCaseName . ". Finished running: " . $flowName . " with result: " . $flowResult . "\n";
    } else {
        echo "Failed running test case: " . $testCaseName . ". Finished running: " . $flowName . " with result: " . $flowResult . " and expected result: " . $expectedResult . "\n";
    }
}

$data = ['RESULT' => 'Success', 'EXECUTABLE_NAME' => 'TestFlow'];
onEvent('EVENT_EXECUTION_FINISHED', $data);
onEvent('SCORE_FINISHED_EVENT', []);
--EXPECT--
