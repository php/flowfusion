--TEST--
Translated seed (inferredbugs-java: bookkeeper/27)
--FILE--
<?php
$ledgerPassword = "aaa";
$numEntriesToWrite = 200;
$maxInt = 2147483647;
$rng = new Random();
$entries = [];
$entriesSize = [];
$ledgerId = 0;

class SyncObj {
    public $counter = 0;
    public $value = false;
}

function createLedger() {
    return random_int(1, 1000); // Simulating ledger creation with a random ID
}

function addEntry($ledgerId, $entry) {
    // Simulate adding an entry to the ledger
}

function openLedger($ledgerId, $password) {
    return new LedgerHandle($ledgerId);
}

class LedgerHandle {
    private $id;

    public function __construct($id) {
        $this->id = $id;
    }

    public function addEntry($entry) {
        // Simulate adding an entry
    }

    public function close() {
        // Simulate closing the ledger
    }

    public function readEntries($start, $end) {
        return []; // Return simulated entries
    }

    public function getId() {
        return $this->id;
    }

    public function getLastAddConfirmed() {
        return random_int(0, 199); // Simulate last confirmed entry
    }
}

$lh = createLedger();
for ($i = 0; $i < $numEntriesToWrite; $i++) {
    $entry = random_int(0, $maxInt);
    addEntry($lh, $entry);
    $entries[] = $entry;
    $entriesSize[] = strlen((string)$entry);
}

$lh->close();
$lh = openLedger($lh->getId(), $ledgerPassword);

$readEntries = $lh->readEntries(0, $numEntriesToWrite - 1);
foreach ($readEntries as $index => $entry) {
    if ($entry !== $entries[$index]) {
        // Simulate an assertion failure
        exit("Entries do not match");
    }
}

$lh->close();
?>
--EXPECT--
