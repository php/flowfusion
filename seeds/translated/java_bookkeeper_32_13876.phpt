--TEST--
Translated seed (inferredbugs-java: bookkeeper/32)
--FILE--
<?php

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$ledgerId = 0;

class SyncObj {
    public $counter = 0;
    public $value = false;
}

function createLedger($password) {
    // Simulates ledger creation
    return random_int(1, 1000);
}

function openLedger($ledgerId, $password) {
    // Simulates opening ledger
    return $ledgerId;
}

function addEntry($ledgerId, $entry) {
    // Simulates adding an entry to a ledger
}

function readEntries($ledgerId) {
    // Simulates reading entries from a ledger
    return [];
}

$sync = new SyncObj();

$ledgerId = createLedger($ledgerPassword);

for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    $entries[] = $entry;
    addEntry($ledgerId, $entry);
    $sync->counter++;
}

$lastConfirmed = $sync->counter - 1;

if ($lastConfirmed !== ($numEntriesToWrite - 1)) {
    echo "Error: Confirmed entries do not match expected count.\n";
}

$readEntries = readEntries($ledgerId);
foreach ($readEntries as $i => $entry) {
    if ($entry !== $entries[$i]) {
        echo "Error: Entry $i mismatch.\n";
    }
}
--EXPECT--
