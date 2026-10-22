--TEST--
Translated seed (inferredbugs-csharp: Arduino/2)
--FILE--
<?php

class EnhancedSerialConnection {
    public $ReadTimeout = 100;
    public $WriteTimeout = 100;
    public $BaudRate = 115200;
    private $isOpen = false;

    public function __construct($port = null, $baudRate = null) {
        if ($baudRate !== null) {
            $this->BaudRate = $baudRate;
        }
    }

    public function Open() {
        $this->isOpen = true;
    }

    public function Close() {
        $this->isOpen = false;
    }
}

function main() {
    $connection1 = new EnhancedSerialConnection();
    $connection2 = new EnhancedSerialConnection("COM1", 115200);

    $connection1->Open();
    $connection1->Close();
    $connection1->Close(); // double close

    $connection2->Open();
    $connection2->Close();
    $connection2->Close(); // double close
}

main();
--EXPECT--
