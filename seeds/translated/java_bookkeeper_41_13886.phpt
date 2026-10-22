--TEST--
Translated seed (inferredbugs-java: bookkeeper/41)
--FILE--
<?php

class SyncObj {
    public $counter = 0;
    public $value = false;
}

$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = mt_rand();
$entries = [];
$entriesSize = [];
$sync = new SyncObj();

$ledgerId = 1; // Simulated ledger ID

// Simulating the writing of entries
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = pack("i", random_int(0, $maxInt));
    $entries[] = $entry;
    $entriesSize[] = strlen($entry);
    $sync->counter++; // Simulate async add entry completed
}

// Simulating a bookie failure
$entriesCountBeforeFailure = $sync->counter;
$sync->counter = 0; // Reset count for simulating failure handling

// Simulate bookie shutdown
// ...

// Simulating reading entries
$sync->value = true; // Simulating that all entries have been read
$numScanned = 0;

if ($sync->value) {
    foreach ($entries as $index => $entry) {
        $retrievedEntry = unpack("i", $entry)[1];
        if ($retrievedEntry !== unpack("i", $entries[$index])[1] || strlen($entry) !== $entriesSize[$index]) {
            // Simulate error handling
            break;
        }
        $numScanned++;
    }
}

if ($numEntriesToWrite - 1 !== $numScanned) {
    // Simulate error handling
    exit(1);
}

// Simulate successful completion
exit(0);
--EXPECT--
