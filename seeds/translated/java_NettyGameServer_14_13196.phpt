--TEST--
Translated seed (inferredbugs-java: NettyGameServer/14)
--FILE--
<?php

class SdServer {
    public function load($element) {}
}

class SdRpcServiceProvider {
    public function load($element) {}
}

class RpcServiceDiscovery {
    private $lock;
    private $sdWorldServers = [];
    private $sdGameServers = [];
    private $sdDbServers = [];
    private $sdRpcServiceProvider;

    public function __construct() {
        $this->lock = fopen('php://temp', 'r+');
    }

    public function init() {
        $xml = simplexml_load_file('config.xml');
        $this->loadServers($xml->world, $this->sdWorldServers);
        $this->loadServers($xml->game, $this->sdGameServers);
        $this->loadServers($xml->db, $this->sdDbServers);

        $this->initWorldConnectedServer();
        $this->initGameConnectedServer();
        $this->initDbConnectServer();

        $this->sdRpcServiceProvider = new SdRpcServiceProvider();
        foreach ($xml->service as $service) {
            $this->sdRpcServiceProvider->load($service);
        }
    }

    private function loadServers($element, &$servers) {
        foreach ($element->server as $childElement) {
            $server = new SdServer();
            $server->load($childElement);
            $servers[] = $server;
        }
    }

    public function initWorldConnectedServer() {}
    public function initGameConnectedServer() {}
    public function initDbConnectServer() {}
}

$discovery = new RpcServiceDiscovery();
$discovery->init();
fclose($discovery->lock);
--EXPECT--
