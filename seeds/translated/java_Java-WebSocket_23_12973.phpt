--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/23)
--FILE--
<?php

class WebSocketServer implements Runnable {
    private $connections = [];
    private $address;
    private $server;
    private $selector;
    private $thread;

    public function __construct($address) {
        $this->address = $address;
    }

    public function start() {
        if ($this->thread) {
            throw new Exception("Already started");
        }
        $this->thread = new Thread($this);
        $this->thread->start();
    }

    public function stop() {
        foreach ($this->connections as $ws) {
            $ws->close();
        }
        $this->thread->interrupt();
        fclose($this->server);
    }

    public function run() {
        $this->server = stream_socket_server("tcp://{$this->address}", $errno, $errstr);
        if (!$this->server) {
            return;
        }

        while (!$this->thread->isInterrupted()) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                $this->connections[] = new WebSocket($client);
            }
            // Example read operation
            foreach ($this->connections as $conn) {
                if ($conn->hasData()) {
                    $conn->handleRead();
                }
            }
        }
    }
}

class WebSocket {
    private $socket;

    public function __construct($socket) {
        $this->socket = $socket;
    }

    public function hasData() {
        return true; 
    }

    public function handleRead() {
        // handle the read logic
    }

    public function close() {
        fclose($this->socket);
    }
}

$server = new WebSocketServer('127.0.0.1:8080');
$server->start();
--EXPECT--
