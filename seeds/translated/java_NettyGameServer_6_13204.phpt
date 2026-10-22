--TEST--
Translated seed (inferredbugs-java: NettyGameServer/6)
--FILE--
<?php

class SdServer {
    public function load($element) {
        // Simulate loading server attributes from XML element
    }
}

class SdRpcServiceProvider {
    public function load($element) {
        // Simulate loading service attributes from XML element
    }
}

$lock = new class() {
    public function lock() { /* implement lock */ }
    public function unlock() { /* implement unlock */ }
};

function getRootElement($file) {
    // Simulate retrieving the root XML element from a file
    return new class {
        public function getChild($name) {
            return new class {
                public function getChildren($name) {
                    return [new SdServer(), new SdServer()]; // Simulating server elements
                }
            };
        }
    };
}

$sdWorldServers = [];
$sdGameServers = [];
$sdDbServers = [];

$rootElement = getRootElement("rpc_server_config.xml");

$element = $rootElement->getChild("world");
foreach ($element->getChildren("server") as $childElement) {
    $sdServer = new SdServer();
    $sdServer->load($childElement);
    $sdWorldServers[] = $sdServer;
}

$element = $rootElement->getChild("game");
foreach ($element->getChildren("server") as $childElement) {
    $sdServer = new SdServer();
    $sdServer->load($childElement);
    $sdGameServers[] = $sdServer;
}

$element = $rootElement->getChild("db");
foreach ($element->getChildren("server") as $childElement) {
    $sdServer = new SdServer();
    $sdServer->load($childElement);
    $sdDbServers[] = $sdServer;
}

$lock->lock();
$lock->unlock();

$sdRpcServiceProvider = new SdRpcServiceProvider();
$rootElement = getRootElement("rpc_service_config.xml");
foreach ($rootElement->getChildren("service") as $childElement) {
    $sdRpcServiceProvider->load($childElement);
}
--EXPECT--
