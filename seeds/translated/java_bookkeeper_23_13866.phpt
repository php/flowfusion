--TEST--
Translated seed (inferredbugs-java: bookkeeper/23)
--FILE--
<?php

$numLedgers = 100;
$numRemovedLedgers = 10;

$createdLedgers = [];
$removedLedgers = [];

// Create ledgers
for ($i = 0; $i < $numLedgers; $i++) {
    $createdLedgers[] = $i;
}

// Randomly remove several ledgers
shuffle($createdLedgers);
for ($i = 0; $i < $numRemovedLedgers; $i++) {
    $ledgerId = $createdLedgers[$i];
    $removedLedgers[] = $ledgerId;
    unset($createdLedgers[$i]);
}

// Garbage collection process
$inGcProgress = false;
$createLatch = false;

$gcThread = function() use (&$inGcProgress, &$createLatch, &$removedLedgers) {
    foreach ($removedLedgers as $ledgerId) {
        if (!$inGcProgress) {
            $inGcProgress = true;
            while (!$createLatch) {
                usleep(1000); // simulate waiting for more ledgers to be created
            }
        }
        echo "Garbage Collected ledger $ledgerId\n";
    }
};

$createThread = function() use (&$inGcProgress, &$createLatch, &$createdLedgers) {
    while (!$inGcProgress) {
        usleep(1000); // simulate waiting for GC to start
    }
    // Create 10 more ledgers
    for ($i = 0; $i < 10; $i++) {
        $createdLedgers[] = max($createdLedgers) + 1; // assuming new ledger IDs are sequential
    }
    echo "Finished creating 10 more ledgers.\n";
    $createLatch = true;
};

$gcThread();
$createThread();

// Final state
foreach ($removedLedgers as $ledger) {
    if (in_array($ledger, $createdLedgers)) {
        exit(1); // simulate assertion failure
    }
}
foreach ($createdLedgers as $ledger) {
    if (!in_array($ledger, $createdLedgers)) {
        exit(1); // simulate assertion failure
    }
}
--EXPECT--
