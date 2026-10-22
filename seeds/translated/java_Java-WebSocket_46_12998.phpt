--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/46)
--FILE--
<?php

class WebSocket {
    const CLOSE_NORMAL = 1000;
    const CLOSE_PROTOCOL_ERROR = 1002;

    private $handshakeComplete = false;
    private $flushAndCloseState = false;
    private $isClosed = false;

    public function decode($socketBuffer) {
        if (!$this->handshakeComplete) {
            $this->decodeHandshake($socketBuffer);
        } else {
            if (!$this->flushAndCloseState) {
                $this->decodeFrames($socketBuffer);
            }
        }
    }

    private function decodeHandshake($socketBuffer) {
        // Simulate a handshake process
        if (strlen($socketBuffer) > 0) {
            $this->handshakeComplete = true; // Assume the handshake is successful
        }
    }

    private function decodeFrames($socketBuffer) {
        if ($this->flushAndCloseState || $this->isClosed) {
            return;
        }
        // Process incoming frames
        $this->processFrame($socketBuffer);
    }

    private function processFrame($frame) {
        if ($frame === "CLOSE") {
            $this->close(self::CLOSE_NORMAL, "Closing connection");
        }
    }

    private function close($code, $message) {
        if (!$this->flushAndCloseState) {
            $this->flushAndCloseState = true;
            $this->isClosed = true; // Simulate closing the connection
        }
    }
}

$webSocket = new WebSocket();
$webSocket->decode("sample handshake");
$webSocket->decode("sample frame");
$webSocket->decode("CLOSE");
--EXPECT--
