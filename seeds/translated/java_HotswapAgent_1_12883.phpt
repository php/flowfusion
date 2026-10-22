--TEST--
Translated seed (inferredbugs-java: HotswapAgent/1)
--FILE--
<?php

class PluginManager {
    public static function getInstance() {
        return new self();
    }

    public function getPluginRegistry() {
        return new PluginRegistry();
    }
}

class PluginRegistry {
    public function initializePlugin($pluginName, $appClassLoader) {
        // Simulate initialization of a plugin
        return "Initialized $pluginName with class loader $appClassLoader";
    }
    
    public function getPlugin($pluginName, $appClassLoader) {
        // Simulate retrieving a plugin instance
        return new Plugin($pluginName);
    }
}

class Plugin {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function someMethod($param1, $param2) {
        return "Called someMethod with $param1 and $param2 on plugin $this->name";
    }
}

function callInitializePlugin($pluginClass, $appClassLoader) {
    return PluginManager::getInstance()->getPluginRegistry()->initializePlugin(
        $pluginClass, $appClassLoader
    );
}

function callPluginMethod($pluginClass, $appClassLoader, $method, $params) {
    $pluginInstance = PluginManager::getInstance()->getPluginRegistry()->getPlugin($pluginClass, $appClassLoader);
    
    if (method_exists($pluginInstance, $method)) {
        return $pluginInstance->$method(...$params);
    } else {
        throw new Exception("Method $method does not exist on plugin class $pluginClass");
    }
}

function main() {
    $pluginClass = "TestPlugin";
    $appClassLoader = "AppClassLoader";

    echo callInitializePlugin($pluginClass, $appClassLoader) . PHP_EOL;

    $result = callPluginMethod($pluginClass, $appClassLoader, "someMethod", ["arg1", "arg2"]);
    echo $result . PHP_EOL;
}

main();
--EXPECT--
