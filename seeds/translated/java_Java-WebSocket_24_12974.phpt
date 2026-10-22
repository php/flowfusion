--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/24)
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
        if ($this->thread) throw new Exception("Already started");
        $this->thread = true;
        $this->server = stream_socket_server("tcp://$this->address");
        while ($this->thread) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                stream_set_blocking($client, false);
                $this->connections[] = $client;
            }
            foreach ($this->connections as $index => $conn) {
                if ($data = fread($conn, 1024)) {
                    fwrite($conn, $data);
                }
                if (feof($conn)) {
                    fclose($conn);
                    unset($this->connections[$index]);
                }
            }
        }
    }

    public function stop() {
        foreach ($this->connections as $conn) {
            fclose($conn);
        }
        $this->thread = false;
        fclose($this->server);
    }
}

$server = new WebSocketServer("127.0.0.1:8080");
$server->start();
--EXPECT--
