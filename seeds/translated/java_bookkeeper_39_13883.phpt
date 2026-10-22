--TEST--
Translated seed (inferredbugs-java: bookkeeper/39)
--FILE--
<?php

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$entries = [];
$entriesSize = [];
$syncCounter = 0;
$syncValue = false;

function addEntry(&$entries, &$entriesSize, $value) {
    $entry = pack('L', $value);
    $entries[] = $entry;
    $entriesSize[] = strlen($entry);
}

function simulateShutdown(&$syncCounter, $numEntriesToWrite) {
    // Simulate the shutdown of a bookie
    // In actual use case, this would affect the system behavior
    // Here we mock a failure by not acknowledging writes
}

$numEntries = 100;
for ($i = 0; $i < $numEntries; $i++) {
    addEntry($entries, $entriesSize, rand(0, $maxInt));
}

simulateShutdown($syncCounter, $numEntriesToWrite);

while ($syncCounter < $numEntriesToWrite) {
    // Wait for entries to be acknowledged
}

$lastAddConfirmed = $numEntries - 1;

$numScanned = 0;
foreach ($entries as $i => $entry) {
    $retrievedEntry = unpack('L', $entry)[1];
    if ($retrievedEntry !== $i) {
        break; // Simulate a failure in reading entries
    }
    $numScanned++;
}

if ($numScanned !== $numEntries) {
    // Reading verification failed
}
--EXPECT--
