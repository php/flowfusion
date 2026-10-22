--TEST--
Translated seed (inferredbugs-csharp: Arduino/1)
--FILE--
<?php

class SerialConnection {
    public $ReadTimeout = 100;
    public $WriteTimeout = 100;
    public $BaudRate = 115200;
    private $isOpen = false;

    public function __construct($port = null, $baudRate = 115200) {
        if ($baudRate !== null) {
            $this->BaudRate = $baudRate;
        }
    }

    public function Open() {
        $this->isOpen = true;
    }

    public function Close() {
        if ($this->isOpen) {
            $this->isOpen = false;
        }
    }
}

function test_serial_connection_constructor_without_parameters() {
    $connection = new SerialConnection();
}

function test_serial_connection_constructor_with_parameters() {
    $connection = new SerialConnection("COM1", 115200);
}

function test_serial_connection_open_and_close() {
    $connection = new SerialConnection();
    $connection->Open();
    $connection->Close();
}

function test_serial_connection_open_and_double_close() {
    $connection = new SerialConnection();
    $connection->Open();
    $connection->Close();
    $connection->Close();
}

test_serial_connection_constructor_without_parameters();
test_serial_connection_constructor_with_parameters();
test_serial_connection_open_and_close();
test_serial_connection_open_and_double_close();

?>
--EXPECT--
