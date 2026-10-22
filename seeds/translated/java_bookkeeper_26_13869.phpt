--TEST--
Translated seed (inferredbugs-java: bookkeeper/26)
--FILE--
<?php
$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$syncedCounter = 0;

class SyncObj {
    public $counter = 0;
    public $lastConfirmed = -1;
}

function addEntry($entry, $sync) {
    global $syncedCounter;
    $sync->counter++;
}

$sync = new SyncObj();

for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    $entries[] = $entry;
    addEntry($entry, $sync);
}

// Simulating the waiting for all entries to be acknowledged
while ($sync->counter < $numEntriesToWrite) {
    // Waiting
}

$ledgerId = 1; // Simulated ledger ID
$length = $numEntriesToWrite * 4;

if ($length != $numEntriesToWrite * 4) {
    die("Ledger length mismatch.");
}

// Simulate reading back
$readEntries = $entries; // Simplified read operation
$readCount = 0;

foreach ($readEntries as $index => $entry) {
    if ($entry !== $entries[$index]) {
        die("Entry mismatch at index $index.");
    }
    $readCount++;
}

if ($readCount !== $numEntriesToWrite) {
    die("Read count mismatch.");
}

// Check for zero entry handling
foreach ($entries as $entry) {
    if ($entry === 0) {
        die("Zero entry found.");
    }
}

// Final ledger closure
--EXPECT--
