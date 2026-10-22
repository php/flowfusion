--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/42)
--FILE--
<?php

class WebSocketImpl {
    private $handshakeComplete = false;
    private $closeHandshakeSent = false;
    private $connectionClosed = false;
    private $tempContiniousFrame = null;
    private $outQueue = [];
    private $inQueue = [];

    public function decode($socketBuffer) {
        if (empty($socketBuffer) || $this->connectionClosed) return;

        if ($this->handshakeComplete) {
            $this->decodeFrames($socketBuffer);
        } else {
            if ($this->decodeHandshake($socketBuffer)) {
                $this->decodeFrames($socketBuffer);
            }
        }
    }

    private function decodeHandshake($socketBuffer) {
        // Simulating a handshake processing scenario
        if (strpos($socketBuffer, "HTTP") !== false) {
            $this->handshakeComplete = true;
            return true;
        }
        return false;
    }

    private function decodeFrames($socketBuffer) {
        // Simulating frame decoding
        if (strpos($socketBuffer, "CLOSE") !== false) {
            $this->closeConnection();
        }
    }

    public function close($code, $message) {
        if (!$this->closeHandshakeSent) {
            $this->closeConnection($code, $message);
            $this->closeHandshakeSent = true;
        }
    }

    protected function closeConnection($code = 1000, $message = "") {
        if ($this->connectionClosed) return;
        $this->connectionClosed = true;
        // Simulate cleaning up resources
    }

    public function send($message) {
        if (!$this->handshakeComplete) {
            throw new Exception("Not yet connected");
        }
        $this->outQueue[] = $message;
    }
}

$ws = new WebSocketImpl();
$ws->decode("GET /chat HTTP/1.1");
$ws->send("Hello World");
$ws->decode("CLOSE");
$ws->close(1000, "Normal Closure");
--EXPECT--
