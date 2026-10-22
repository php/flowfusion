--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/44)
--FILE--
<?php

class WebSocketServer {
    private $address;
    private $connections = [];
    private $isClosed = false;
    private $buffers = [];
    private $queueSize = 0;

    public function __construct($port) {
        $this->address = 'localhost:' . $port;
    }

    public function start() {
        $server = stream_socket_server("tcp://{$this->address}", $errno, $errstr);
        if (!$server) {
            throw new Exception("Error: $errstr");
        }

        while (!$this->isClosed) {
            $read = array_merge([$server], $this->connections);
            stream_select($read, $write = null, $except = null, 0, 200000);

            foreach ($read as $socket) {
                if ($socket === $server) {
                    $client = stream_socket_accept($server);
                    $this->connections[] = $client;
                } else {
                    $data = fread($socket, 2048);
                    if ($data === false || $data === '') {
                        $this->closeConnection($socket);
                    } else {
                        $this->handleMessage($socket, $data);
                    }
                }
            }
        }
        fclose($server);
    }

    public function closeConnection($socket) {
        $index = array_search($socket, $this->connections);
        if ($index !== false) {
            fclose($socket);
            unset($this->connections[$index]);
        }
    }

    public function handleMessage($socket, $data) {
        $this->buffers[] = $data;
        if (count($this->buffers) > 10) {
            // Simulate a memory issue by allowing only a fixed number of buffers
            array_shift($this->buffers);
        }
        // Here we could process the message further
    }

    public function stop() {
        $this->isClosed = true;
        foreach ($this->connections as $socket) {
            fclose($socket);
        }
        $this->connections = [];
    }
}

$server = new WebSocketServer(8080);
$server->start();
--EXPECT--
