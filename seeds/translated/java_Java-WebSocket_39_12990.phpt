--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/39)
--FILE--
<?php
class WebSocketClient {
    private $uri;
    private $conn;
    private $channel;
    private $selector;
    private $thread;

    public function __construct($serverURI) {
        $this->uri = $serverURI;
    }

    public function connect() {
        if ($this->thread !== null) throw new Exception("already/still connected");
        $this->thread = new Thread([$this, 'run']);
        $this->thread->start();
    }

    public function close() {
        if ($this->thread !== null) {
            $this->thread->interrupt();
            if ($this->selector !== null) $this->selector->wakeup();
        }
    }

    public function run() {
        try {
            $this->tryToConnect();
            while ($this->channel->isOpen()) {
                // Simulate event loop
                $this->selector->select();
                $this->processKeys();
            }
        } catch (Exception $e) {
            $this->onError($e);
        }
    }

    private function tryToConnect() {
        $this->channel = stream_socket_client("tcp://{$this->uri}", $errno, $errstr);
        if (!$this->channel) throw new Exception("Connection failed: $errstr");
        $this->selector = new Selector();
        $this->selector->register($this->channel, Selector::OP_CONNECT);
    }

    private function processKeys() {
        // Process socket events
    }

    private function onError($e) {
        // Handle error
    }
}

$client = new WebSocketClient("localhost:8080");
$client->connect();
--EXPECT--
