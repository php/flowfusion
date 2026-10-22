--TEST--
Translated seed (inferredbugs-java: NettyGameServer/2)
--FILE--
<?php

class SdServer {
    public function load($element) {
        // Simulate loading server configuration
    }
}

class RpcServiceDiscovery {

    protected $lock;
    protected $sdWorldServers = [];
    protected $sdGameServers = [];
    protected $sdDbServers = [];

    public function __construct() {
        $this->lock = fopen('php://temp', 'r+');
    }

    public function initWorldConnectedServer() {
        // Simulate initialization of world servers
    }

    public function initGameConnectedServer() {
        // Simulate initialization of game servers
    }

    public function initDbConnectServer() {
        // Simulate initialization of DB servers
    }

    public function init() {
        $xml = simplexml_load_file('path/to/config.xml');
        $this->sdWorldServers = $this->loadServers($xml->world->server);
        $this->sdGameServers = $this->loadServers($xml->game->server);
        $this->sdDbServers = $this->loadServers($xml->db->server);

        flock($this->lock, LOCK_EX);
        $this->initWorldConnectedServer();
        $this->initGameConnectedServer();
        $this->initDbConnectServer();
        flock($this->lock, LOCK_UN);
    }

    protected function loadServers($servers) {
        $serverList = [];
        foreach ($servers as $serverElement) {
            $sdServer = new SdServer();
            $sdServer->load($serverElement);
            $serverList[] = $sdServer;
        }
        return $serverList;
    }

    public function run() {
        $this->init();
    }
}

$rpcServiceDiscovery = new RpcServiceDiscovery();
$rpcServiceDiscovery->run();
--EXPECT--
