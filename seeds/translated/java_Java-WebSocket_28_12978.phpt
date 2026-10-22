--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/28)
--FILE--
<?php

class WebSocketClient {
    private $uri;
    private $socket;
    private $selector;
    private $thread;

    public function __construct($uri) {
        $this->uri = $uri;
    }

    public function connect() {
        $this->thread = new Thread([$this, 'run']);
        $this->thread->start();
    }

    public function close() {
        $this->thread->interrupt();
        if ($this->selector) {
            $this->selector->wakeup();
        }
    }

    public function run() {
        $this->socket = stream_socket_client("tcp://{$this->uri}", $errno, $errstr);
        if (!$this->socket) {
            return;
        }
        stream_set_blocking($this->socket, false);
        $this->selector = stream_select([$this->socket], $write = null, $except = null, null);
        while (true) {
            $data = fread($this->socket, 1024);
            if ($data === false) break;
            // Simulating message handling
        }
        fclose($this->socket);
    }
}

$client = new WebSocketClient('localhost:8080');
$client->connect();
sleep(2); // Simulate activity
$client->close();
--EXPECT--
