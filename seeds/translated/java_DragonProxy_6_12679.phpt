--TEST--
Translated seed (inferredbugs-java: DragonProxy/6)
--FILE--
<?php

class DragonProxy {
    private $bedrockPort;
    private $javaPort;

    public function __construct($bedrockPort, $javaPort) {
        $this->bedrockPort = $bedrockPort;
        $this->javaPort = $javaPort;
    }

    public function shutdown() {
        // Shutdown logic here
    }

    public function getConsole() {
        return new Console();
    }
}

class Console {
    public function start() {
        // Console start logic here
    }
}

function main($args) {
    echo "Starting DragonProxy...\n";

    // Check PHP version
    if (version_compare(PHP_VERSION, '7.0.0', '<')) {
        echo "DragonProxy requires PHP 7.0 or higher! Current version: " . PHP_VERSION . "\n";
        return;
    }

    $bedrockPort = isset($args['bedrockPort']) ? intval($args['bedrockPort']) : -1;
    $javaPort = isset($args['javaPort']) ? intval($args['javaPort']) : -1;

    $startTime = microtime(true);
    $proxy = new DragonProxy($bedrockPort, $javaPort);

    register_shutdown_function([$proxy, 'shutdown']);

    $bootTime = microtime(true) - $startTime;
    echo "Done (" . number_format($bootTime, 2) . "s)!\n";

    $proxy->getConsole()->start();
}

main(array_slice($argv, 1));
--EXPECT--
