--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/43)
--FILE--
<?php

class WebSocketImpl {
    public $handshakeComplete = false;
    public $closeHandshakeSent = false;
    public $connectionClosed = false;
    private $tmpHandshakeBytes = null;
    
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
        if ($this->tmpHandshakeBytes === null) {
            $this->tmpHandshakeBytes = $socketBuffer;
        } else {
            $this->tmpHandshakeBytes .= $socketBuffer;
        }
        
        if (strlen($this->tmpHandshakeBytes) > 0) {
            $this->handshakeComplete = true; // Simulating a successful handshake for the example
            return true;
        }
        
        return false;
    }
    
    private function decodeFrames($socketBuffer) {
        if ($this->handshakeComplete) {
            // Simulating frame decoding
            if ($socketBuffer === "CLOSE") {
                $this->close(1000, "Normal Closure");
            }
        }
    }
    
    public function close($code, $message) {
        if (!$this->closeHandshakeSent) {
            if ($this->handshakeComplete) {
                $this->closeHandshakeSent = true;
                $this->connectionClosed = true;
            }
        }
    }
}

$ws = new WebSocketImpl();
$ws->decode("HELLO");
$ws->decode("CLOSE");
--EXPECT--
