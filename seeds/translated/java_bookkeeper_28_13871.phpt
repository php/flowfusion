--TEST--
Translated seed (inferredbugs-java: bookkeeper/28)
--FILE--
<?php

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new \Random\Random();
$entries = [];
$ledgerId = 0;

function createLedger($password) {
    // Simulate creating a ledger and return a unique ID
    return rand(1, 1000);
}

function openLedger($id, $password) {
    // Simulate opening a ledger and returning a handle
    return $id;
}

function addEntry($ledgerId, $entry) {
    // Simulate adding an entry to the ledger
}

function readEntries($ledgerId, $start, $end) {
    global $entries;
    return array_slice($entries, $start, $end - $start + 1);
}

$ledgerId = createLedger($ledgerPassword);
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    $entries[] = $entry;
    addEntry($ledgerId, $entry);
}

$readEntries = readEntries($ledgerId, 0, $numEntriesToWrite - 1);
foreach ($readEntries as $i => $entry) {
    if ($entry !== $entries[$i]) {
        trigger_error("Entry mismatch at index $i", E_USER_WARNING);
    }
}

$ledgerId2 = createLedger($ledgerPassword);
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    $entries[] = $entry;
    addEntry($ledgerId2, $entry);
}
$readEntries2 = readEntries($ledgerId2, 0, $numEntriesToWrite - 1);
foreach ($readEntries2 as $i => $entry) {
    if ($entry !== $entries[$numEntriesToWrite + $i]) {
        trigger_error("Entry mismatch at index " . ($numEntriesToWrite + $i), E_USER_WARNING);
    }
}
--EXPECT--
