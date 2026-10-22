--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/18)
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
        if ($this->thread !== null) {
            throw new Exception("Already started");
        }
        $this->thread = true;
        $this->server = stream_socket_server("tcp://{$this->address}", $errno, $errstr);
        if (!$this->server) {
            throw new Exception("Could not bind to address: $errstr");
        }
        while ($this->thread) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                $this->connections[] = $client;
                fwrite($client, "Connected\n");
                $this->handleClient($client);
            }
        }
    }

    public function stop() {
        $this->thread = false;
        fclose($this->server);
        foreach ($this->connections as $conn) {
            fclose($conn);
        }
    }
    
    private function handleClient($client) {
        while (!feof($client)) {
            $data = fread($client, 1024);
            if ($data !== false) {
                fwrite($client, "Received: " . $data);
            } else {
                break;
            }
        }
        fclose($client);
    }
}

$server = new WebSocketServer('localhost:8080');
$server->start();
--EXPECT--
