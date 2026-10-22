--TEST--
Translated seed (inferredbugs-java: bookkeeper/19)
--FILE--
<?php

$ledgerPassword = "admin";
$rng = new Random();
$entries = [];
$ledgerList = [];

function createAndAddEntriesToLedger() {
    global $rng, $entries;
    $numEntriesToWrite = 20;
    $ledgerId = uniqid();
    for ($i = 0; $i < $numEntriesToWrite; $i++) {
        $entry = pack("L", $rng->nextInt());
        $entries[] = $entry;
    }
    return $ledgerId;
}

$ledgerList[] = createAndAddEntriesToLedger();
$ledgerList[] = createAndAddEntriesToLedger();
$ledgerList[] = createAndAddEntriesToLedger();

$bookieLedgerIndex = [];

$bookieToLedgerIndex = array_fill(0, 3, array_fill(0, 3, 0));
for ($i = 0; $i < 3; $i++) {
    $bookieToLedgerIndex[$i] = $ledgerList;
}

if (count($bookieToLedgerIndex) !== 3) {
    exit("Missed few bookies in the bookie-ledger mapping!\n");
}

foreach ($bookieToLedgerIndex as $ledgers) {
    if (count($ledgers) !== 3) {
        exit("Missed few ledgers in the bookie-ledger mapping!\n");
    }
    foreach ($ledgers as $ledgerId) {
        if (!in_array($ledgerId, $ledgerList)) {
            exit("Unknown ledger-bookie mapping\n");
        }
    }
}

class Random {
    public function nextInt() {
        return mt_rand(0, PHP_INT_MAX);
    }
}
--EXPECT--
