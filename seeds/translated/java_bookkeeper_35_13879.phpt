--TEST--
Translated seed (inferredbugs-java: bookkeeper/35)
--FILE--
<?php

$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = rand();
$entries = [];
$ledgerId = 0;

try {
    $ledgerId = 1; // Simulating ledger creation

    for ($i = 0; $i < $numEntriesToWrite; $i++) {
        $entry = pack('L', rand(0, $maxInt));
        $entries[] = $entry;

        // Simulating asynchronous entry addition
        if ($i === 100) { // Simulate a failure case at the mid-point
            throw new Exception("Simulated exception during write.");
        }
    }

    // Simulating ledger closure
    $ledgerLength = count($entries) * 4;
    
    // Simulating ledger opening and reading
    $readEntries = [];
    foreach ($entries as $entry) {
        $readEntries[] = unpack('L', $entry)[1];
    }

    for ($i = 0; $i < count($readEntries); $i++) {
        if ($entries[$i] !== $readEntries[$i]) {
            throw new Exception("Data mismatch at index $i.");
        }
    }

    // Outputs for debugging
    echo "Ledger ID: $ledgerId\n";
    echo "Number of entries written: " . count($entries) . "\n";
    echo "Ledger length before closing: $ledgerLength\n";
    echo "Ledger length after opening: $ledgerLength\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
--EXPECT--
