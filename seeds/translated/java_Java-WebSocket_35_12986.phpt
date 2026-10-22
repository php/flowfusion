--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/35)
--FILE--
<?php

class WebSocketClient {

    private $uri;
    private $conn;
    private $thread;
    private $selector;

    public function __construct($serverURI) {
        $this->uri = $serverURI;
    }

    public function connect() {
        if ($this->thread) {
            throw new Exception("Already connected");
        }
        $this->thread = pthread_create([$this, 'run']);
    }

    public function run() {
        $this->tryToConnect();
        while (true) {
            if (!$this->selector) break;
            // Simulate processing and handle connection
            // Here we would process reads, writes, etc.
            sleep(1);
        }
    }

    private function tryToConnect() {
        $this->selector = stream_socket_client("tcp://{$this->uri}", $errno, $errstr);
        if (!$this->selector) {
            throw new Exception("Connection failed: $errstr");
        }
        stream_set_blocking($this->selector, false);
    }

    public function close() {
        if ($this->thread) {
            $this->thread = null;
            fclose($this->selector);
        }
    }
}

$client = new WebSocketClient("localhost:8080");
$client->connect();
sleep(5);
$client->close();
--EXPECT--
