--TEST--
Translated seed (inferredbugs-java: NettyGameServer/11)
--FILE--
<?php

class SdServer {
    public function load($element) {}
}

class SdRpcServiceProvider {
    public function load($element) {}
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

    public function initWorldConnectedServer() {}
    public function initGameConnectedServer() {}
    public function initDbConnectServer() {}

    public function init() {
        $rootElement = simplexml_load_file('rpc_server_config.xml');

        $this->sdWorldServers = $this->loadServers($rootElement->world->server);
        $this->sdGameServers = $this->loadServers($rootElement->game->server);
        $this->sdDbServers = $this->loadServers($rootElement->db->server);

        $this->initWorldConnectedServer();
        $this->initGameConnectedServer();
        $this->initDbConnectServer();

        $this->sdRpcServiceProvider = new SdRpcServiceProvider();
        $serviceElements = $rootElement->service;
        foreach ($serviceElements as $childElement) {
            $this->sdRpcServiceProvider->load($childElement);
        }
    }

    protected function loadServers($servers) {
        $result = [];
        foreach ($servers as $childElement) {
            $sdServer = new SdServer();
            $sdServer->load($childElement);
            $result[] = $sdServer;
        }
        return $result;
    }
}

$rpcServiceDiscovery = new RpcServiceDiscovery();
$rpcServiceDiscovery->init();
--EXPECT--
