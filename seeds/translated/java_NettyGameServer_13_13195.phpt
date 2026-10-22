--TEST--
Translated seed (inferredbugs-java: NettyGameServer/13)
--FILE--
<?php

class SdServer {
    public function load($element) {
        // Simulating loading from an element, no-op
    }
}

class JdomUtils {
    public static function getRootElement($file) {
        // Simulated XML parsing for demonstration
        return new SimpleXMLElement('<?xml version="1.0"?><root><world><server/><server/></world><game><server/><server/></game><db><server/><server/></db></root>');
    }
}

class RpcServiceDiscovery {
    private $lock;
    private $sdWorldServers;
    private $sdGameServers;
    private $sdDbServers;

    public function __construct() {
        $this->lock = fopen('php://temp', 'r+'); // Using a temporary file as a lock
    }

    public function init() {
        $rootElement = JdomUtils::getRootElement('dummy_config.xml');

        $this->sdWorldServers = [];
        foreach ($rootElement->world->server as $element) {
            $sdServer = new SdServer();
            $sdServer->load($element);
            $this->sdWorldServers[] = $sdServer;
        }

        $this->sdGameServers = [];
        foreach ($rootElement->game->server as $element) {
            $sdServer = new SdServer();
            $sdServer->load($element);
            $this->sdGameServers[] = $sdServer;
        }

        $this->sdDbServers = [];
        foreach ($rootElement->db->server as $element) {
            $sdServer = new SdServer();
            $sdServer->load($element);
            $this->sdDbServers[] = $sdServer;
        }

        flock($this->lock, LOCK_EX); // Locking
        // ... (simulating server updates under lock)
        flock($this->lock, LOCK_UN); // Unlocking
    }
}

$rpcServiceDiscovery = new RpcServiceDiscovery();
$rpcServiceDiscovery->init();
--EXPECT--
