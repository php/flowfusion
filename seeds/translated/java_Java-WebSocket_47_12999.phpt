--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/47)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $address;
    private $server;
    private $isClosed = false;
    private $decoders = [];
    private $buffers;

    public function __construct($address, $decoderCount) {
        $this->address = $address;
        $this->buffers = new \SplQueue();
        for ($i = 0; $i < $decoderCount; $i++) {
            $this->decoders[] = new WebSocketWorker($this);
        }
    }

    public function start() {
        $this->server = stream_socket_server("tcp://{$this->address}", $errno, $errstr);
        if (!$this->server) {
            throw new RuntimeException("Could not bind to address: $errstr");
        }
        foreach ($this->decoders as $decoder) {
            $decoder->start();
        }
        while (!$this->isClosed) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                $this->connections[] = $client;
                fwrite($client, "Welcome!\n");
            }
        }
    }

    public function stop() {
        $this->isClosed = true;
        foreach ($this->connections as $client) {
            fclose($client);
        }
        fclose($this->server);
    }

    public function allocateBuffers() {
        if ($this->buffers->count() < 10) {
            $this->buffers->enqueue(fopen('php://temp', 'r+'));
        }
    }

    public function takeBuffer() {
        return $this->buffers->dequeue();
    }
}

class WebSocketWorker extends Thread {
    private $server;

    public function __construct($server) {
        $this->server = $server;
    }

    public function run() {
        while (true) {
            $this->server->allocateBuffers();
            // Simulate processing incoming messages...
            sleep(1); // Simulated work
        }
    }
}

$server = new WebSocketServer('0.0.0.0:8080', 4);
$server->start();
--EXPECT--
