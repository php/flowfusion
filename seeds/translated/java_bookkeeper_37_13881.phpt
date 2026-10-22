--TEST--
Translated seed (inferredbugs-java: bookkeeper/37)
--FILE--
<?php

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$entries = [];
$rng = new \Random\Random(); // Simulating random number generation
$ledgerId = 1; // Placeholder for ledger ID
$syncCounter = 0;

function asyncAddEntry(&$entry, &$syncCounter) {
    global $entries;
    array_push($entries, $entry);
    $syncCounter++;
}

for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    asyncAddEntry($entry, $syncCounter);
}

while ($syncCounter < $numEntriesToWrite) {
    // Simulating waiting for entries to be acknowledged
}

$ledgerLength = count($entries);
assert($ledgerLength === $numEntriesToWrite, "Ledger length before closing: {$ledgerLength}");

$ledgerId++;
$syncCounter = 0;

for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    asyncAddEntry($entry, $syncCounter);
}

while ($syncCounter < $numEntriesToWrite) {
    // Simulating waiting for entries to be acknowledged
}

$ledgerLengthAfter = count($entries);
assert($ledgerLengthAfter === $numEntriesToWrite, "Ledger length after opening: {$ledgerLengthAfter}");
--EXPECT--
