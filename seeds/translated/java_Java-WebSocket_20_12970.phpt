--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/20)
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
        if ($this->thread !== null) {
            throw new Exception("Already started");
        }
        $this->thread = new Thread(fn() => $this->run());
        $this->thread->start();
    }

    public function stop() {
        foreach ($this->connections as $ws) {
            $ws->close();
        }
        $this->thread->interrupt();
        $this->server->close();
    }

    public function run() {
        $this->server = stream_socket_server("tcp://{$this->address}", $errno, $errstr);
        stream_set_blocking($this->server, false);
        $this->selector = new Selector();

        while (!$this->thread->isInterrupted()) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                $this->connections[] = new WebSocket($client);
            }
            foreach ($this->connections as $conn) {
                if ($conn->hasData()) {
                    $conn->read();
                }
            }
            usleep(100); // Avoid busy waiting
        }
    }
}

class WebSocket {
    private $socket;

    public function __construct($socket) {
        $this->socket = $socket;
    }

    public function hasData() {
        return true; // Placeholder for actual data checking
    }

    public function read() {
        // Placeholder for actual read operation
    }

    public function close() {
        fclose($this->socket);
    }
}

$server = new WebSocketServer('localhost:8080');
$server->start();
--EXPECT--
