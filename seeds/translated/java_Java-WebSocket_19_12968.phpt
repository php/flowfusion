--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/19)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $address;
    private $server;
    private $thread;

    public function __construct($address = 'localhost:8080') {
        $this->address = $address;
    }

    public function start() {
        if ($this->thread !== null) {
            throw new Exception("Already started");
        }
        $this->thread = true;
        $this->run();
    }

    public function stop() {
        foreach ($this->connections as $conn) {
            $conn->close();
        }
        $this->thread = null;
    }

    public function run() {
        $this->server = stream_socket_server("tcp://{$this->address}", $errno, $errstr);
        if (!$this->server) {
            die("Error: $errstr ($errno)");
        }
        
        while ($this->thread) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                $this->connections[] = new WebSocket($client);
            }
            foreach ($this->connections as $conn) {
                if ($conn->hasMessage()) {
                    $conn->sendResponse();
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

    public function close() {
        fclose($this->socket);
    }

    public function hasMessage() {
        $data = fread($this->socket, 1024);
        return $data !== false && $data !== '';
    }

    public function sendResponse() {
        fwrite($this->socket, "Response");
    }
}

$server = new WebSocketServer();
$server->start();
--EXPECT--
