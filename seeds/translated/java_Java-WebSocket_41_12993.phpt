--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/41)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $server;
    private $port;
    
    public function __construct($port) {
        $this->port = $port;
        $this->server = stream_socket_server("tcp://0.0.0.0:$port", $errno, $errstr);
        if (!$this->server) {
            throw new Exception("Could not create server: $errstr");
        }
        stream_set_blocking($this->server, false);
    }

    public function start() {
        while (true) {
            $client = @stream_socket_accept($this->server, 0);
            if ($client) {
                $this->connections[] = $client;
                $this->onOpen($client);
            }
            foreach ($this->connections as $key => $conn) {
                $data = fread($conn, 2048);
                if ($data === false) {
                    fclose($conn);
                    unset($this->connections[$key]);
                    $this->onClose($conn);
                } elseif ($data) {
                    $this->onMessage($conn, $data);
                }
            }
            usleep(100000); // Prevent busy waiting
        }
    }

    protected function onOpen($conn) {
        fwrite($conn, "Welcome to the WebSocket server!\n");
    }

    protected function onMessage($conn, $message) {
        fwrite($conn, "Received: $message");
    }

    protected function onClose($conn) {
        fwrite($conn, "Connection closed.\n");
    }

    public function stop() {
        foreach ($this->connections as $conn) {
            fclose($conn);
        }
        fclose($this->server);
    }
}

$server = new WebSocketServer(8080);
$server->start();
--EXPECT--
