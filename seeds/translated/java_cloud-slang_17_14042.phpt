--TEST--
Translated seed (inferredbugs-java: cloud-slang/17)
--FILE--
<?php

$asyncLoopStatement = ['varName' => 'item', 'list' => range(1, 5)];
$runEnv = [];
$executionRuntimeServices = [];
$nodeName = 'asyncLoopTest';
$nextStepId = 2;
$branchBeginStep = 1;
$refId = 'branchId';

try {
    $splitData = $asyncLoopStatement['list'];

    foreach ($splitData as $splitItem) {
        $branchRuntimeEnvironment = $runEnv;
        $branchContext = [];
        $branchContext[$asyncLoopStatement['varName']] = $splitItem;

        // Simulating branch creation
        $executionRuntimeServices[] = [
            'branchBeginStep' => $branchBeginStep,
            'refId' => $refId,
            'context' => $branchContext
        ];
    }

    // Simulating result processing
    $branchesResult = [];
    foreach ($executionRuntimeServices as $branch) {
        $result = ($branch['context']['item'] % 2 == 0) ? 'SUCCESS' : 'FAILURE';
        $branchesResult[] = $result;
    }

    $asyncLoopResult = 'SUCCESS';
    foreach ($branchesResult as $branchResult) {
        if ($branchResult === 'FAILURE') {
            $asyncLoopResult = 'FAILURE';
            break;
        }
    }

    $nextStepPosition = ($asyncLoopResult === 'SUCCESS') ? 3 : 4;

} catch (Exception $e) {
    echo "Error running: $nodeName: " . $e->getMessage();
}
--EXPECT--
