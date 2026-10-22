--TEST--
Translated seed (inferredbugs-java: Thunder/3)
--FILE--
<?php
class ZookeeperInvoker {
    private $client;

    public function create($address, $sessionTimeout, $connectTimeout, $connectWaitTime) {
        if ($this->client !== null) {
            throw new Exception("Zookeeper client isn't null, it has been initialized already");
        }

        $this->client = $address; // Simulating client creation
    }

    public function start() {
        if ($this->client === null) {
            throw new Exception("Zookeeper client is null");
        }
        // Simulating client start
    }

    public function validateStartedStatus() {
        if ($this->client === null) {
            throw new Exception("Zookeeper client is null");
        }
        // Assume client is started
    }

    public function pathExist($path) {
        $this->validateStartedStatus();
        return $path === "/valid/path"; // Simulating a check
    }

    public function createPath($path) {
        $this->validateStartedStatus();
        // Simulating path creation
    }

    public function deletePath($path) {
        $this->validateStartedStatus();
        // Simulating path deletion
    }

    public function getData($path) {
        $this->validateStartedStatus();
        return $path === "/valid/path" ? "data" : null; // Simulating data retrieval
    }

    public function setData($path, $data) {
        $this->validateStartedStatus();
        // Simulating data setting
    }
}

$invoker = new ZookeeperInvoker();
$invoker->create("localhost", 10000, 10000, 500);
$invoker->start();
if ($invoker->pathExist("/valid/path")) {
    $data = $invoker->getData("/valid/path");
    $invoker->setData("/valid/path", "new data");
    $invoker->deletePath("/valid/path");
}
--EXPECT--
