--TEST--
Translated seed (inferredbugs-csharp: AlterNats/11)
--FILE--
<?php

class NatsServer {
    private $port;
    private $process;

    public function __construct($argument = "") {
        $this->port = rand(5000, 6000);
        $this->process = popen("nats-server -p {$this->port} {$argument}", 'r');

        $this->checkServerStart();
        echo "OK to Process Start, Port: $this->port\n";
    }

    private function checkServerStart() {
        $start = microtime(true);
        $timeout = 5; // seconds

        while ((microtime(true) - $start) < $timeout) {
            $client = @fsockopen("localhost", $this->port, $errno, $errstr, 1);
            if ($client) {
                fclose($client);
                return;
            }
            usleep(500000); // 500 milliseconds
        }

        throw new Exception("Could not start NATS server in time.");
    }

    public function __destruct() {
        if (is_resource($this->process)) {
            pclose($this->process);
        }
    }

    public function createClientConnection() {
        return new NatsConnection($this->clientOptions());
    }

    private function clientOptions() {
        return [
            'url' => "localhost:{$this->port}"
        ];
    }
}

class NatsCluster {
    private $servers = [];

    public function __construct() {
        $this->servers[] = new NatsServer("--cluster_name test-cluster -cluster nats://localhost:" . rand(10000, 11000));
        $this->servers[] = new NatsServer("--cluster_name test-cluster -cluster nats://localhost:" . rand(10000, 11000) . " -routes nats://localhost:" . $this->servers[0]->port);
        $this->servers[] = new NatsServer("--cluster_name test-cluster -cluster nats://localhost:" . rand(10000, 11000) . " -routes nats://localhost:" . $this->servers[0]->port);
    }

    public function __destruct() {
        foreach ($this->servers as $server) {
            unset($server);
        }
    }
}

function main() {
    $cluster = new NatsCluster();
}

main();
--EXPECT--
