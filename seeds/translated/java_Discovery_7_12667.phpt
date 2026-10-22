--TEST--
Translated seed (inferredbugs-java: Discovery/7)
--FILE--
<?php

class MyDiscoveryEnabledStrategy {
    
    private $zuulStrategyContextHolder;
    private $pluginAdapter;

    public function __construct($zuulStrategyContextHolder, $pluginAdapter) {
        $this->zuulStrategyContextHolder = $zuulStrategyContextHolder;
        $this->pluginAdapter = $pluginAdapter;
    }

    public function apply($server, $metadata) {
        return $this->applyFromHeader($server, $metadata);
    }

    private function applyFromHeader($server, $metadata) {
        $token = $this->zuulStrategyContextHolder->getHeader('token');
        $serviceId = $this->pluginAdapter->getServerServiceId($server);
        
        echo "Zuul端负载均衡用户定制触发：token={$token}, serviceId={$serviceId}, metadata=" . json_encode($metadata) . "\n";
        
        $filterToken = "abc";
        if (!empty($token) && strpos($token, $filterToken) !== false) {
            echo "过滤条件：当Token含有'{$filterToken}'的时候，不能被Ribbon负载均衡到\n";
            return false;
        }

        return true;
    }
}

class ZuulStrategyContextHolder {
    public function getHeader($header) {
        return $_SERVER[$header] ?? null;
    }
}

class PluginAdapter {
    public function getServerServiceId($server) {
        return $server['serviceId'] ?? 'unknown';
    }
}

$server = ['serviceId' => 'exampleService'];
$metadata = ['key' => 'value'];
$zuulStrategyContextHolder = new ZuulStrategyContextHolder();
$pluginAdapter = new PluginAdapter();

$strategy = new MyDiscoveryEnabledStrategy($zuulStrategyContextHolder, $pluginAdapter);
$strategy->apply($server, $metadata);
--EXPECT--
