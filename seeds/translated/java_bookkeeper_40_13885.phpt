--TEST--
Translated seed (inferredbugs-java: bookkeeper/40)
--FILE--
<?php

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$syncCounter = 0;
$syncValue = false;

class SyncObj {
    public $counter = 0;
    public $value = false;
}

function createLedger($numBookies, $digestType, $password) {
    // Simulate creating a ledger
    return rand(1, 1000); // Return a dummy ledger ID
}

function addEntry($ledgerHandle, $data) {
    // Simulate adding an entry to the ledger
}

function getLastAddConfirmed($ledgerHandle) {
    return rand(0, 199); // Simulate getting the last confirmed entry
}

function readEntries($ledgerHandle, $start, $end) {
    return array_map(function($i) {
        return strval($i);
    }, range($start, $end));
}

$ledgerHandle = createLedger(3, 2, $ledgerPassword);

for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    addEntry($ledgerHandle, $entry);
    $entries[] = $entry;
}

$syncCounter++;
$syncValue = true;

if (getLastAddConfirmed($ledgerHandle) == $numEntriesToWrite - 1) {
    $numScanned = 0;
    $ls = readEntries($ledgerHandle, 0, getLastAddConfirmed($ledgerHandle));
    foreach ($ls as $index => $entry) {
        if ($entry !== strval($entries[$index])) {
            exit("Entry mismatch at index $index");
        }
        $numScanned++;
    }
}

if ($numScanned !== $numEntriesToWrite) {
    exit("Mismatch in number of scanned entries");
}
--EXPECT--
