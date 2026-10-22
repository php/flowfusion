--TEST--
Translated seed (inferredbugs-java: NettyGameServer/8)
--FILE--
<?php

class SdServer {
    public function load($element) {
        // Simulated load operation
    }
}

class SdRpcServiceProvider {
    public function load($element) {
        // Simulated load operation
    }
}

class RpcServiceDiscovery {

    protected $lock;
    protected $sdWorldServers = [];
    protected $sdGameServers = [];
    protected $sdDbServers = [];
    protected $sdRpcServiceProvider;

    public function __construct() {
        $this->lock = [];
    }

    public function initWorldConnectedServer() {
        // Simulated connection initialization
    }

    public function initGameConnectedServer() {
        // Simulated connection initialization
    }

    public function initDbConnectServer() {
        // Simulated connection initialization
    }

    public function init() {
        $rootElement = simplexml_load_string('<root><world><server/><server/></world><game><server/><server/></game><db><server/><server/></db></root>');

        $this->sdWorldServers = $this->loadServers($rootElement->world->server);
        $this->sdGameServers = $this->loadServers($rootElement->game->server);
        $this->sdDbServers = $this->loadServers($rootElement->db->server);

        $this->initWorldConnectedServer();
        $this->initGameConnectedServer();
        $this->initDbConnectServer();

        $this->sdRpcServiceProvider = new SdRpcServiceProvider();
        $serviceElements = simplexml_load_string('<services><service/><service/></services>')->service;
        foreach ($serviceElements as $childElement) {
            $this->sdRpcServiceProvider->load($childElement);
        }
    }

    protected function loadServers($childrenElements) {
        $servers = [];
        foreach ($childrenElements as $childElement) {
            $sdServer = new SdServer();
            $sdServer->load($childElement);
            $servers[] = $sdServer;
        }
        return $servers;
    }
}

$rpcServiceDiscovery = new RpcServiceDiscovery();
$rpcServiceDiscovery->init();
--EXPECT--
