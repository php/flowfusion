--TEST--
Translated seed (inferredbugs-java: NettyGameServer/4)
--FILE--
<?php

class SdServer {
    public function load($element) {
        // Simulate loading server configuration
    }
}

class SdRpcServiceProvider {
    public function load($element) {
        // Simulate loading service configuration
    }
}

class RpcServiceDiscovery {

    protected $lock;
    protected $sdWorldServers = [];
    protected $sdGameServers = [];
    protected $sdDbServers = [];
    protected $sdRpcServiceProvider;

    public function __construct() {
        $this->lock = fopen('php://temp', 'r+'); // Using file pointer for a lock
    }

    public function init() {
        $rootElement = simplexml_load_file('rpc_server_config.xml'); // Simulating config loading
        $this->loadServers($rootElement, 'world', $this->sdWorldServers);
        $this->loadServers($rootElement, 'game', $this->sdGameServers);
        $this->loadServers($rootElement, 'db', $this->sdDbServers);
        
        // Simulate locking
        flock($this->lock, LOCK_EX);
        $this->sdRpcServiceProvider = new SdRpcServiceProvider();
        flock($this->lock, LOCK_UN);
    }

    protected function loadServers($root, $type, &$serverList) {
        foreach ($root->$type->server as $childElement) {
            $server = new SdServer();
            $server->load($childElement);
            $serverList[] = $server;
        }
    }

    public function startup() {
        $this->init();
    }

    public function shutdown() {
        // Simulate stopping services
    }

}

$rpcServiceDiscovery = new RpcServiceDiscovery();
$rpcServiceDiscovery->startup();
$rpcServiceDiscovery->shutdown();
--EXPECT--
