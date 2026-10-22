--TEST--
Translated seed (inferredbugs-java: bookkeeper/18)
--FILE--
<?php

$ledgerPassword = "admin";
$entries = [];
$rng = new Random();
$ledgerList = [];

function createAndAddEntriesToLedger($rng, &$entries) {
    $numEntriesToWrite = 20;
    $lh = random_int(1, 1000); // Simulate ledger ID
    for ($i = 0; $i < $numEntriesToWrite; $i++) {
        $entry = random_int(0, PHP_INT_MAX);
        $entries[] = $entry;
    }
    return $lh;
}

$lh1 = createAndAddEntriesToLedger($rng, $entries);
$ledgerList[] = $lh1;

$lh2 = createAndAddEntriesToLedger($rng, $entries);
$ledgerList[] = $lh2;

$lh3 = createAndAddEntriesToLedger($rng, $entries);
$ledgerList[] = $lh3;

// Simulating the mapping
$bookieToLedgerIndex = [
    "bookie1" => [$lh1, $lh2],
    "bookie2" => [$lh2, $lh3],
    "bookie3" => [$lh3, $lh1],
];

if (count($bookieToLedgerIndex) !== 3) {
    die("Missed few bookies in the bookie-ledger mapping!");
}

foreach ($bookieToLedgerIndex as $ledgers) {
    if (count($ledgers) !== 2) {
        die("Missed few ledgers in the bookie-ledger mapping!");
    }
    foreach ($ledgers as $ledgerId) {
        if (!in_array($ledgerId, $ledgerList)) {
            die("Unknown ledger-bookie mapping");
        }
    }
}

?>
--EXPECT--
