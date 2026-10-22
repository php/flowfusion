--TEST--
Translated seed (inferredbugs-java: bookkeeper/29)
--FILE--
<?php

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$entries = [];
$syncCounter = 0;

function asyncAddEntry(&$entries, $entry, &$syncCounter) {
    $entries[] = $entry;
    $syncCounter++;
}

$ledgerId = rand(1, 1000); // Simulating ledger creation
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    asyncAddEntry($entries, $entry, $syncCounter);
}

while ($syncCounter < $numEntriesToWrite) {
    // Waiting for async operations to complete
}

assert(count($entries) === $numEntriesToWrite);

$ledgerId2 = rand(1, 1000); // Simulating another ledger
$entries2 = [];
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    $entries2[] = $entry;
}

assert(count($entries2) === $numEntriesToWrite);

foreach ($entries as $index => $entry) {
    assert($entry >= 0 && $entry <= $maxInt);
}

foreach ($entries2 as $index => $entry) {
    assert($entry >= 0 && $entry <= $maxInt);
}
--EXPECT--
