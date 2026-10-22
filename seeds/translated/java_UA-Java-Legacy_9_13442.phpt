--TEST--
Translated seed (inferredbugs-java: UA-Java-Legacy/9)
--FILE--
<?php

$handshakeTimeout = 10 * 60; // 10 minutes
$pendingRequests = [];
$maxRecvChunkSize = 8192;

// Simulating a buffer monitor state for chunk processing
class BufferMonitorState {
    const Normal = 0;
    const Triggered = 1;
    const Unreachable = 2;
}

class ChunkProcessor {
    private $state;

    public function __construct() {
        $this->state = BufferMonitorState::Normal;
    }

    public function setState($newState) {
        $this->state = $newState;
    }

    public function getState() {
        return $this->state;
    }

    public function processChunk($chunk) {
        $chunkSize = unpack("V", substr($chunk, 4, 4))[1];

        if ($chunkSize < 12) {
            echo "Error: Chunk size too small\n";
            return;
        }

        if ($chunkSize > $GLOBALS['maxRecvChunkSize']) {
            echo "Error: Chunk size ($chunkSize) exceeded maximum ({$GLOBALS['maxRecvChunkSize']})\n";
            return;
        }

        if (strlen($chunk) >= $chunkSize) {
            // Simulates reading a fully available chunk
            echo "Processing chunk of size $chunkSize\n";
        } else {
            // Simulate waiting for more data
            echo "Waiting for more data...\n";
        }
    }
}

$chunkProcessor = new ChunkProcessor();
$buffer = str_repeat("\0", 8) . pack("V", 13); // Simulated buffer of 8 bytes + chunk size of 13

$chunkProcessor->processChunk($buffer);
$chunkProcessor->setState(BufferMonitorState::Triggered);
$chunkProcessor->processChunk($buffer);
--EXPECT--
