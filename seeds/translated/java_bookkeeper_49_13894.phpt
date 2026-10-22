--TEST--
Translated seed (inferredbugs-java: bookkeeper/49)
--FILE--
<?php

class LedgerHandle {
    const INVALID_ENTRY_ID = -1;
    public $lastAddPushed = self::INVALID_ENTRY_ID;
    public $lastAddConfirmed = self::INVALID_ENTRY_ID;
    public $length = 0;
}

class LedgerRecoveryOp {
    private $lh;
    private $numResponsesPending;
    private $cb;

    public function __construct($lh, $cb) {
        $this->lh = $lh;
        $this->cb = $cb;
        $this->numResponsesPending = 1; // Simulating single response
    }

    public function initiate() {
        $this->lh->lastAddPushed = $this->lh->lastAddConfirmed = 0; // Simulating retrieved data
        $this->doRecoveryRead();
    }

    private function doRecoveryRead() {
        $this->lh->lastAddConfirmed++;
        $this->readComplete(0);
    }

    public function readComplete($rc) {
        $this->lh->lastAddConfirmed--;
        if ($rc === 0) {
            $data = "entry_data"; // Simulated entry data
            $this->lh->length = strlen($data);
            $this->addComplete(0);
            return;
        }
        // Simulating closure of ledger
        $this->cb(0);
    }

    public function addComplete($rc) {
        if ($rc !== 0) {
            $this->cb($rc);
            return;
        }
        $this->doRecoveryRead();
    }
}

$lh = new LedgerHandle();
$callback = function($rc) {
    if ($rc === 0) {
        echo "Operation completed successfully.\n";
    } else {
        echo "Operation failed with code: $rc\n";
    }
};

$op = new LedgerRecoveryOp($lh, $callback);
$op->initiate();
--EXPECT--
