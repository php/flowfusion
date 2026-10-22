--TEST--
Translated seed (inferredbugs-csharp: AAEmu/13)
--FILE--
<?php

class PacketStream {
    private $data;
    private $position;

    public function __construct($data) {
        $this->data = $data;
        $this->position = 0;
    }

    public function readUInt32() {
        $value = unpack("L", substr($this->data, $this->position, 4))[1];
        $this->position += 4;
        return $value;
    }

    public function readInt16() {
        $value = unpack("s", substr($this->data, $this->position, 2))[1];
        $this->position += 2;
        return $value;
    }
}

class CSStartDuelPacket {
    private $id;
    private $errorMessage;

    public function __construct() {
        // Constructor logic if needed
    }

    public function read($stream) {
        $this->id = $stream->readUInt32();
        $this->errorMessage = $stream->readInt16();
        echo "StartDuel, Id: {$this->id}, ErrorMessage: {$this->errorMessage}\n";
    }
}

$data = pack('L s', 4294967295, -1); // Pack a maximum unsigned 32-bit int and -1 as signed 16-bit int
$stream = new PacketStream($data);
$packet = new CSStartDuelPacket();
$packet->read($stream);
--EXPECT--
