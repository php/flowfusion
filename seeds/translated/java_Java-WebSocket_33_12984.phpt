--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/33)
--FILE--
<?php
class WebSocketClient {
    private $uri;
    private $socket;
    private $thread;

    public function __construct($uri) {
        if (!$uri) throw new InvalidArgumentException();
        $this->uri = $uri;
        $this->socket = null;
    }

    public function connect() {
        if ($this->thread) throw new Exception("already/still connected");
        $this->thread = new Thread(function() {
            $this->socket = stream_socket_client($this->uri);
            if (!$this->socket) return;
            stream_set_blocking($this->socket, false);
            while (!feof($this->socket)) {
                $data = fread($this->socket, 1024);
                if ($data) {
                    echo "Received: $data\n";
                }
            }
        });
        $this->thread->start();
    }

    public function send($message) {
        if ($this->socket) {
            fwrite($this->socket, $message);
        }
    }

    public function close() {
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
        }
        if ($this->thread) {
            $this->thread->interrupt();
            $this->thread = null;
        }
    }
}

$client = new WebSocketClient('tcp://localhost:8080');
$client->connect();
$client->send("Hello");
$client->close();
?>
--EXPECT--
