--TEST--
Translated seed (inferredbugs-java: bookkeeper/30)
--FILE--
<?php
$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$entriesSize = [];

function randomInt() {
    return rand(0, 2147483647);
}

function createLedger($password) {
    return $password; // dummy ledger handle
}

function openLedger($ledgerId, $password) {
    return $ledgerId; // dummy ledger handle
}

function addEntry($ledgerHandle, $entry) {
    // Simulate adding entry
}

function readEntries($ledgerHandle, $start, $end) {
    // Simulate reading entries
    return [];
}

$lh = createLedger($ledgerPassword);

for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = randomInt();
    $entries[] = pack("L", $entry);
    addEntry($lh, $entries[$i]);
}

$lh = openLedger(0, $ledgerPassword);
$readEntries = readEntries($lh, 0, $numEntriesToWrite - 1);
$expectedCount = count($entries);
$actualCount = count($readEntries);

for ($i = 0; $i < $expectedCount; $i++) {
    $origEntry = unpack("L", $entries[$i])[1];
    $retrEntry = unpack("L", $readEntries[$i])[1];
}

$lh = createLedger($ledgerPassword);
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    addEntry($lh, pack("L", 0));
}

$lh = openLedger(0, $ledgerPassword);
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $result = readEntries($lh, 0, $i);
}
--EXPECT--
