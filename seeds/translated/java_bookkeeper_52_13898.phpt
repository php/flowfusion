--TEST--
Translated seed (inferredbugs-java: bookkeeper/52)
--FILE--
<?php

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$entriesSize = [];

class SyncObj {
    public $counter = 0;
    public $value = false;
    public $lastConfirmed = -1;
}

function simulateAsyncAddEntry($entry, $sync) {
    $sync->counter++;
}

function testReadWriteAsyncSingleClient() {
    global $numEntriesToWrite, $maxInt, $rng, $entries;
    $sync = new SyncObj();
    
    for ($i = 0; $i < $numEntriesToWrite; $i++) {
        $entry = pack("N", $rng->nextInt($maxInt));
        $entries[] = $entry;
        simulateAsyncAddEntry($entry, $sync);
    }

    while ($sync->counter < $numEntriesToWrite) {
        usleep(100); // Simulating waiting
    }

    $ledgerId = 1; // Simulated ledger ID
    $lastConfirmed = $numEntriesToWrite - 1;

    if ($lastConfirmed !== $sync->counter - 1) {
        throw new Exception("Error adding");
    }

    // Reading back entries
    $readEntries = array_slice($entries, 0, $numEntriesToWrite);
    foreach ($readEntries as $i => $entry) {
        $retrEntry = unpack("N", $entry)[1];
        if ($retrEntry !== unpack("N", $entries[$i])[1]) {
            throw new Exception("Checking entry $i for equality failed");
        }
    }
}

testReadWriteAsyncSingleClient();
--EXPECT--
