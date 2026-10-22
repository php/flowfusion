--TEST--
Translated seed (inferredbugs-java: Discovery/3)
--FILE--
<?php

class GatewayStrategyContext {
    private static $currentContext;

    public static function getCurrentContext() {
        return self::$currentContext;
    }

    public static function setCurrentContext($context) {
        self::$currentContext = $context;
    }

    public static function clearCurrentContext() {
        self::$currentContext = null;
    }
}

class Server {
    private $appName;

    public function __construct($appName) {
        $this->appName = $appName;
    }

    public function getMetaInfo() {
        return $this;
    }

    public function getAppName() {
        return $this->appName;
    }

    public function __toString() {
        return $this->appName;
    }
}

class MyDiscoveryEnabledAdapter {
    public function apply($server, $metadata) {
        $context = GatewayStrategyContext::getCurrentContext();
        $token = isset($context['token']) ? $context['token'] : null;

        GatewayStrategyContext::clearCurrentContext();

        $serviceId = strtolower($server->getMetaInfo()->getAppName());

        echo "Gateway load balancing triggered: serviceId={$serviceId}, host={$server}, metadata=" . json_encode($metadata) . ", context=" . json_encode($context) . "\n";

        $filterToken = "abc";
        if (!empty($token) && strpos($token, $filterToken) !== false) {
            echo "Filter condition: when Token contains '{$filterToken}', it cannot be load balanced\n";
            return false;
        }

        return true;
    }
}

function main() {
    $context = ['token' => 'abc123'];
    GatewayStrategyContext::setCurrentContext($context);

    $server = new Server("TestService");
    $adapter = new MyDiscoveryEnabledAdapter();
    $adapter->apply($server, []);

    GatewayStrategyContext::clearCurrentContext();
}

main();
--EXPECT--
