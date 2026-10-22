--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/21)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $socket;
    private $thread;

    public function __construct($host, $port) {
        $this->socket = stream_socket_server("tcp://$host:$port", $errno, $errstr);
        if (!$this->socket) {
            throw new Exception("Failed to create socket: $errstr");
        }
    }

    public function start() {
        if ($this->thread) {
            throw new Exception("Server already started");
        }
        $this->thread = true;
        while ($this->thread) {
            $client = @stream_socket_accept($this->socket);
            if ($client) {
                $this->connections[] = $client;
            }
            foreach ($this->connections as $index => $conn) {
                $data = fread($conn, 2048);
                if ($data === false) {
                    fclose($conn);
                    unset($this->connections[$index]);
                }
            }
        }
    }

    public function stop() {
        $this->thread = false;
        fclose($this->socket);
    }
}

$server = new WebSocketServer('127.0.0.1', 8080);
$server->start();
--EXPECT--
