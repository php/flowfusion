--TEST--
Translated seed (inferredbugs-java: NettyGameServer/12)
--FILE--
<?php

class RpcServiceDiscovery {

    private $lock;
    private $sdWorldServers;
    private $sdGameServers;
    private $sdDbServers;

    public function __construct() {
        $this->lock = [];
        $this->sdWorldServers = [];
        $this->sdGameServers = [];
        $this->sdDbServers = [];
    }

    public function initWorldConnectedServer() {
        // Simulate server initialization
    }

    public function initGameConnectedServer() {
        // Simulate server initialization
    }

    public function initDbConnectServer() {
        // Simulate server initialization
    }

    public function init() {
        $rootElement = simplexml_load_file('config.xml');

        $this->sdWorldServers = [];
        foreach ($rootElement->world->server as $child) {
            $sdServer = new StdClass();
            $sdServer->name = (string) $child->name;
            $this->sdWorldServers[] = $sdServer;
        }

        $this->sdGameServers = [];
        foreach ($rootElement->game->server as $child) {
            $sdServer = new StdClass();
            $sdServer->name = (string) $child->name;
            $this->sdGameServers[] = $sdServer;
        }

        $this->sdDbServers = [];
        foreach ($rootElement->db->server as $child) {
            $sdServer = new StdClass();
            $sdServer->name = (string) $child->name;
            $this->sdDbServers[] = $sdServer;
        }

        $this->initWorldConnectedServer();
        $this->initGameConnectedServer();
        $this->initDbConnectServer();

        $this->sdRpcServiceProvider = new StdClass();
        $rootElement = simplexml_load_file('service_config.xml');
        foreach ($rootElement->service as $child) {
            $this->sdRpcServiceProvider->name = (string) $child->name;
        }
    }
}

$rpcServiceDiscovery = new RpcServiceDiscovery();
$rpcServiceDiscovery->init();
--EXPECT--
