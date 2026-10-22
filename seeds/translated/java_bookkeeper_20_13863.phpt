--TEST--
Translated seed (inferredbugs-java: bookkeeper/20)
--FILE--
<?php

$ledgerPassword = 'admin';
$rng = new Random();
$entries = [];
$ledgerList = [];
$bookieToLedgerIndex = [];

function createAndAddEntriesToLedger($rng, $entries) {
    $numEntriesToWrite = 20;
    $ledgerId = random_int(1, 1000); // Simulating ledger ID generation
    for ($i = 0; $i < $numEntriesToWrite; $i++) {
        $entry = random_int(0, PHP_INT_MAX);
        $entries[] = $entry;
    }
    return [$ledgerId, $entries];
}

list($lh1, $entries) = createAndAddEntriesToLedger($rng, $entries);
$ledgerList[] = $lh1;
list($lh2, $entries) = createAndAddEntriesToLedger($rng, $entries);
$ledgerList[] = $lh2;
list($lh3, $entries) = createAndAddEntriesToLedger($rng, $entries);
$ledgerList[] = $lh3;

$bookieToLedgerIndex['bookie1'] = [$lh1, $lh2, $lh3];
$bookieToLedgerIndex['bookie2'] = [$lh1, $lh2, $lh3];
$bookieToLedgerIndex['bookie3'] = [$lh1, $lh2, $lh3];

if (count($bookieToLedgerIndex) !== 3) {
    die("Missed few bookies in the bookie-ledger mapping!");
}

foreach ($bookieToLedgerIndex as $ledgers) {
    if (count($ledgers) !== 3) {
        die("Missed few ledgers in the bookie-ledger mapping!");
    }
    foreach ($ledgers as $ledgerId) {
        if (!in_array($ledgerId, $ledgerList)) {
            die("Unknown ledger-bookie mapping");
        }
    }
}

function stopZKCluster() {
    // Simulate stopping Zookeeper
}

stopZKCluster();
try {
    // Simulating exception
    throw new Exception("Must throw exception as bookies are not running!");
} catch (Exception $e) {
    // expected behaviour
}

?>
--EXPECT--
