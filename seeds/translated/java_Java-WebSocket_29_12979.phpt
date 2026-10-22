--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/29)
--FILE--
<?php
class WebSocketClient {
    private $uri;
    private $client;
    private $selector;
    private $thread;
    private $closed = false;

    public function __construct($uri) {
        $this->uri = $uri;
    }

    public function connect() {
        if ($this->thread) {
            throw new Exception("already connected");
        }
        $this->thread = true;
        $this->run();
    }

    public function close() {
        if ($this->thread) {
            $this->closed = true;
            if ($this->selector) {
                stream_select([$this->selector], $write = null, $except = null, 0, 0);
            }
        }
    }

    private function run() {
        $remote = stream_socket_client("tcp://{$this->uri}", $errno, $errstr);
        if (!$remote) {
            throw new Exception("Could not connect to {$this->uri}");
        }
        $this->client = $remote;
        $this->selector = $remote;

        while (!$this->closed) {
            $read = [$this->selector];
            stream_select($read, $write = null, $except = null, 0, 100000);
            if (in_array($this->selector, $read)) {
                $data = fread($this->client, 8192);
                if ($data === false || $data === '') {
                    break;
                }
                // Simulate processing the received data
            }
        }

        fclose($this->client);
        $this->client = null;
    }
}

$client = new WebSocketClient("localhost:8080");
$client->connect();
$client->close();
--EXPECT--
