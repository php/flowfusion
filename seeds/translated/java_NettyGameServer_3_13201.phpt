--TEST--
Translated seed (inferredbugs-java: NettyGameServer/3)
--FILE--
<?php

class SdServer {
    public function load($element) {
        // Simulate server loading from XML element
    }
}

class RpcServiceDiscovery {
    private $lock;
    private $sdWorldServers;
    private $sdGameServers;
    private $sdDbServers;

    public function __construct() {
        $this->lock = new class() {
            private $isLocked = false;
            public function lock() { $this->isLocked = true; }
            public function unlock() { $this->isLocked = false; }
            public function isLocked() { return $this->isLocked; }
        };
    }

    public function init() {
        $rootElement = simplexml_load_file('config.xml'); // Simulating the file load

        $this->sdWorldServers = [];
        foreach ($rootElement->world->server as $childElement) {
            $sdServer = new SdServer();
            $sdServer->load($childElement);
            $this->sdWorldServers[] = $sdServer;
        }

        $this->sdGameServers = [];
        foreach ($rootElement->game->server as $childElement) {
            $sdServer = new SdServer();
            $sdServer->load($childElement);
            $this->sdGameServers[] = $sdServer;
        }

        $this->sdDbServers = [];
        foreach ($rootElement->db->server as $childElement) {
            $sdServer = new SdServer();
            $sdServer->load($childElement);
            $this->sdDbServers[] = $sdServer;
        }

        $this->lock->lock();
        $this->initWorldConnectedServer();
        $this->initGameConnectedServer();
        $this->initDbConnectServer();
        $this->lock->unlock();
    }

    public function initWorldConnectedServer() {
        // Simulating server initialization
    }

    public function initGameConnectedServer() {
        // Simulating server initialization
    }

    public function initDbConnectServer() {
        // Simulating server initialization
    }
}

$rpcServiceDiscovery = new RpcServiceDiscovery();
$rpcServiceDiscovery->init();
--EXPECT--
