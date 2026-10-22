--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/4)
--FILE--
<?php

class TLSServer {
    private $free = true;

    public function isFree() {
        return $this->free;
    }

    public function occupy() {
        $this->free = false;
    }

    public function release() {
        $this->free = true;
    }
}

class ServerManager {
    private static $instance;
    private $serverList = [];

    private function __construct() {}

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new ServerManager();
        }
        return self::$instance;
    }

    public function addServer($server) {
        $this->serverList[] = $server;
    }

    public function getFreeServer() {
        $startSearch = microtime(true);
        if (empty($this->serverList)) {
            return null;
        }
        $i = 0;
        while (true) {
            $server = $this->serverList[$i % count($this->serverList)];
            if ($server->isFree()) {
                $server->occupy();
                return $server;
            }
            $i++;
            if ($startSearch < microtime(true) - 10) {
                throw new RuntimeException("Could not find a free Server.");
            }
        }
    }
}

$manager = ServerManager::getInstance();
$manager->addServer(new TLSServer());
$manager->addServer(new TLSServer());
$server = $manager->getFreeServer();
$server->release();
--EXPECT--
