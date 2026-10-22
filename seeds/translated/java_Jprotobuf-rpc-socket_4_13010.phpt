--TEST--
Translated seed (inferredbugs-java: Jprotobuf-rpc-socket/4)
--FILE--
<?php

class NamingService {
    private $servers;

    public function __construct($servers) {
        $this->servers = $servers;
    }

    public function list() {
        return $this->servers;
    }
}

class LoadBalanceProxy {
    private $servers;
    
    public function __construct($servers) {
        $this->servers = $servers;
    }

    public function getNextServer() {
        static $index = 0;
        if (empty($this->servers)) return null;
        $server = $this->servers[$index];
        $index = ($index + 1) % count($this->servers);
        return $server;
    }
}

class RpcClient {}

class HaProtobufRpcProxy {
    private $rpcClient;
    private $namingService;
    private $loadBalanceProxy;
    private $instance;

    public function __construct($rpcClient, NamingService $namingService) {
        $this->rpcClient = $rpcClient;
        $this->namingService = $namingService;
        $this->loadBalanceProxy = new LoadBalanceProxy($this->namingService->list());
    }

    public function proxy() {
        if ($this->instance) return $this->instance;

        $server = $this->loadBalanceProxy->getNextServer();
        if (!$server) throw new Exception("No servers available");

        $this->instance = $server;
        return $this->instance;
    }
}

$servers = [
    '127.0.0.1:8080',
    '127.0.0.1:8081',
    '127.0.0.1:8082',
];
$namingService = new NamingService($servers);
$rpcClient = new RpcClient();
$proxy = new HaProtobufRpcProxy($rpcClient, $namingService);
$proxy->proxy();
--EXPECT--
