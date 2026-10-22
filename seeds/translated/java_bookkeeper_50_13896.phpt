--TEST--
Translated seed (inferredbugs-java: bookkeeper/50)
--FILE--
<?php

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = mt_rand();
$entries = [];
$entriesSize = [];

$ledgerId = 0;

$sync = [
    'counter' => 0,
    'value' => false,
    'rc' => 0
];

function asyncAddEntry($entry, &$sync) {
    global $maxInt, $entries, $entriesSize;
    $sync['rc'] = 0;
    $entries[] = $entry;
    $entriesSize[] = count($entry);
    $sync['counter']++;
}

function openLedger($id, $password) {
    // Simulate opening a ledger
    return $id;
}

function createLedger($password) {
    // Simulate creating a ledger and returning an ID
    global $ledgerId;
    return $ledgerId++;
}

function addEntry($ledger, $entry) {
    // Simulate adding an entry to the ledger
}

function readEntries($ledger, $start, $end) {
    // Simulate reading entries from the ledger
    global $entries;
    return array_slice($entries, $start, $end - $start + 1);
}

function readLastConfirmed($ledger) {
    // Simulate reading the last confirmed entry
    return count($GLOBALS['entries']) - 1;
}

$ledger = createLedger($ledgerPassword);
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = pack("N", random_int(0, $maxInt));
    asyncAddEntry($entry, $sync);
}

$ledgerLength = count($entries) * 4;

if ($ledgerLength !== count($entries) * 4) {
    exit("Error: Ledger length mismatch.");
}

$ledger = openLedger($ledgerId, $ledgerPassword);
if (readLastConfirmed($ledger) !== ($numEntriesToWrite - 1)) {
    exit("Error: Last confirmed entry mismatch.");
}

$readEntries = readEntries($ledger, 0, $numEntriesToWrite - 1);
for ($i = 0; $i < count($readEntries); $i++) {
    if ($readEntries[$i] !== $entries[$i]) {
        exit("Error: Entry {$i} mismatch.");
    }
}
--EXPECT--
