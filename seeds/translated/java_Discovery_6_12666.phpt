--TEST--
Translated seed (inferredbugs-java: Discovery/6)
--FILE--
<?php

class MyDiscoveryEnabledStrategy {
    public function apply($server, $metadata) {
        return $this->applyFromHeader($server, $metadata);
    }

    private function applyFromHeader($server, $metadata) {
        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        $serviceId = $this->getServerServiceId($server);

        echo "Gateway负载均衡用户定制触发：token={$token}, serviceId={$serviceId}, metadata=" . json_encode($metadata) . "\n";

        $filterToken = "abc";
        if (!empty($token) && strpos($token, $filterToken) !== false) {
            echo "过滤条件：当Token含有'{$filterToken}'的时候，不能被负载均衡到\n";
            return false;
        }

        return true;
    }

    private function getServerServiceId($server) {
        // Simulating service ID retrieval; in practice, this would fetch from server properties
        return $server['id'] ?? 'defaultServiceId';
    }
}

$strategy = new MyDiscoveryEnabledStrategy();
$server = ['id' => 'service1'];
$metadata = ['key' => 'value'];
$strategy->apply($server, $metadata);
--EXPECT--
