--TEST--
Translated seed (inferredbugs-java: bookkeeper/54)
--FILE--
<?php

$numEntries = 100;
$gcWaitTime = 1000;
$minorCompactionThreshold = 0.1;
$majorCompactionThreshold = 0.5;
$minorCompactionInterval = 2 * $gcWaitTime / 1000;
$majorCompactionInterval = 4 * $gcWaitTime / 1000;

$entrySize = 1024;
$msg = str_repeat("a", $entrySize);

function prepareData($numEntryLogs, $changeNum) {
    global $numEntries, $msg;
    $num1 = 2;
    $num3 = (int)($numEntries * 0.7);
    $num2 = $numEntries - $num3 - $num1;

    $lhs = [];
    for ($i = 0; $i < 3; $i++) {
        $lhs[$i] = []; // Simulating ledger handle
    }

    for ($n = 0; $n < $numEntryLogs; $n++) {
        for ($k = 0; $k < $num1; $k++) {
            $lhs[0][] = $msg; // Adding entries
        }
        for ($k = 0; $k < $num2; $k++) {
            $lhs[1][] = $msg;
        }
        for ($k = 0; $k < $num3; $k++) {
            $lhs[2][] = $msg;
        }
        if ($changeNum) {
            $num2--;
            $num3++;
        }
    }
    return $lhs;
}

function verifyLedger($id, $startEntryId, $endEntryId) {
    global $msg;
    // Simulate reading entries based on the ledger ID
    for ($i = $startEntryId; $i <= $endEntryId; $i++) {
        if ($msg !== "a" . $i) {
            exit(1); // Simulating assertion failure
        }
    }
}

$lhs = prepareData(3, false);
sleep($gcWaitTime / 1000); // Simulating deletion of ledgers
if (in_array($msg, $lhs[0])) {
    verifyLedger(1, 0, count($lhs[0]) - 1);
}
--EXPECT--
