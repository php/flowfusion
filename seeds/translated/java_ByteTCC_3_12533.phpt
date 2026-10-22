--TEST--
Translated seed (inferredbugs-java: ByteTCC/3)
--FILE--
<?php

class CompensableTransaction {
    private $transactionStatus;
    const STATUS_ACTIVE = 0;
    const STATUS_COMMITTED = 1;
    const STATUS_ROLLEDBACK = 2;

    public function __construct() {
        $this->transactionStatus = self::STATUS_ACTIVE;
    }

    public function commit() {
        if ($this->transactionStatus != self::STATUS_ACTIVE) {
            throw new Exception("Transaction already committed or rolled back");
        }
        // Simulate a potential operation that could fail and invoke the rollback
        if (rand(0, 1) === 0) {
            $this->transactionStatus = self::STATUS_COMMITTED;
        } else {
            $this->rollback();
        }
    }

    public function rollback() {
        if ($this->transactionStatus != self::STATUS_ACTIVE) {
            throw new Exception("Transaction already committed or rolled back");
        }
        $this->transactionStatus = self::STATUS_ROLLEDBACK;
    }

    public function getStatus() {
        return $this->transactionStatus;
    }
}

function main() {
    $transaction = new CompensableTransaction();
    try {
        $transaction->commit();
        echo "Transaction Status: " . $transaction->getStatus();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

main();
--EXPECT--
