--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/16)
--FILE--
<?php

class ProtocolMessage {
    public $protocolMessageType;
}

class AlertMessage extends ProtocolMessage {
    public $level;
    public $description;

    public function __construct($level, $description) {
        $this->protocolMessageType = 'ALERT';
        $this->level = $level;
        $this->description = $description;
    }
}

class FinishedMessage extends ProtocolMessage {
    public $records = [];

    public function addRecord($record) {
        $this->records[] = $record;
    }
}

class Record {
    public $plainRecordBytes;

    public function setPlainRecordBytes($bytes) {
        $this->plainRecordBytes = $bytes;
    }
}

function createRecordWithBadPadding() {
    $plain = array_fill(0, 64, 255);
    $record = new Record();
    $record->setPlainRecordBytes($plain);
    return $record;
}

$versions = ['TLS10', 'TLS11', 'TLS12'];
$ciphers = ['someCipherSuite']; // Assume this is initialized to supported cipher suites
$vulnerable = false;

foreach ($versions as $pv) {
    foreach ($ciphers as $cs) {
        // Simulate setting protocol version and cipher suite
        $finishedMessage = new FinishedMessage();
        $record = createRecordWithBadPadding();
        $finishedMessage->addRecord($record);

        // Simulate sending alert instead of finished (bad padding)
        $alertMessage = new AlertMessage(1, 22); // 1=warning, 22=bad padding

        // Simulate receiving the message
        $lastMessage = $alertMessage; // This simulates the last received message

        if ($lastMessage->protocolMessageType == 'ALERT') {
            if ($lastMessage->description == 22) {
                echo "  Vulnerable\n";
                $vulnerable = true;
            } else {
                echo "  Not Vulnerable / Not supported\n";
            }
        }

        // Simulate closing connection
    }
}

if ($vulnerable) {
    echo "VULNERABLE\n";
} else {
    echo "NOT VULNERABLE\n";
}
--EXPECT--
