--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/5)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $address;
    private $server;
    private $thread;

    public function __construct($address) {
        $this->address = $address;
    }

    public function start() {
        if ($this->thread !== null) throw new Exception("Already started");
        $this->thread = true;
        $this->run();
    }

    public function stop() {
        foreach ($this->connections as $conn) {
            $this->close($conn);
        }
        $this->thread = null;
        fclose($this->server);
    }

    private function run() {
        $this->server = stream_socket_server("tcp://{$this->address}", $errno, $errstr);
        if (!$this->server) return;

        while ($this->thread) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                $this->connections[] = $client;
                $this->handshake($client);
            }

            foreach ($this->connections as $key => $conn) {
                $data = fread($conn, 1024);
                if ($data === false || $data === '') {
                    $this->close($conn);
                    unset($this->connections[$key]);
                } else {
                    $this->processMessage($conn, $data);
                }
            }
        }
    }

    private function handshake($client) {
        // Simplified handshake logic
        fwrite($client, "HTTP/1.1 101 Switching Protocols\r\n\r\n");
    }

    private function processMessage($conn, $data) {
        // Echo message back
        fwrite($conn, "Received: $data");
    }

    private function close($conn) {
        fclose($conn);
    }
}

$server = new WebSocketServer("127.0.0.1:8080");
$server->start();
--EXPECT--
