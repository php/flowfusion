--TEST--
Translated seed (inferredbugs-java: config-toolkit/2)
--FILE--
<?php

class ConfigNode {
    private $properties = [];

    public function __construct($properties) {
        $this->properties = $properties;
    }

    public function getProperty($key) {
        return $this->properties[$key] ?? null;
    }

    public function register($observer) {
        // Simulate registration
    }
}

class ConfigFactory {
    public function getConfigNode($name) {
        return new ConfigNode([
            'string_property_key' => 'Welcome here.',
            'int_property_key' => '1123'
        ]);
    }
}

$configFactory = new ConfigFactory();
$propertyGroup1 = $configFactory->getConfigNode("property-group1");

$stringProperty = $propertyGroup1->getProperty("string_property_key");
if ($stringProperty !== "Welcome here.") {
    exit(1);
}

$intProperty = $propertyGroup1->getProperty("int_property_key");
if ((int)$intProperty !== 1123) {
    exit(1);
}

$lock = fopen("php://temp", "r+");
flock($lock, LOCK_SH);
while (true) {
    usleep(100000); // Wait indefinitely (simulating lock)
}
flock($lock, LOCK_UN);
fclose($lock);
--EXPECT--
