--TEST--
Translated seed (inferredbugs-java: FastDFS_Client/10)
--FILE--
<?php

class SoketServer {
    private $port = 22122;
    private $serverSocket;

    public function service() {
        $i = 0;
        $this->serverSocket = stream_socket_server("tcp://0.0.0.0:{$this->port}", $errno, $errstr);
        if (!$this->serverSocket) {
            echo "Error: $errstr ($errno)\n";
            return;
        }

        while (true) {
            $i++;
            $socket = @stream_socket_accept($this->serverSocket); 
            if ($socket) {
                echo "第{$i}个客户端成功连接！\n";
                // Simulate handling the socket in a new "thread" (here, just a function call)
                $this->handle($socket);
            }
        }
    }

    private function handle($socket) {
        // Mock handling, just close the connection
        fclose($socket);
    }
}

$server = new SoketServer();
echo "------服务启动--------\n";
$server->service();

?>
--EXPECT--
