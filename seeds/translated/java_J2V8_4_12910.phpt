--TEST--
Translated seed (inferredbugs-java: J2V8/4)
--FILE--
<?php

class V8Runtime {
    private static $lock = null;
    private static $runtimeCounter = 0;
    private $v8RuntimePtr;

    public static function createV8Runtime() {
        if (self::$lock === null) {
            self::$lock = new stdClass();
        }
        if (!self::$runtimeCounter) {
            self::loadLibraries();
        }
        self::$runtimeCounter++;
        return new self();
    }

    private static function loadLibraries() {
        // Simulate library loading; in practice, this might involve checking if certain PHP extensions are available.
    }

    public function __construct() {
        $this->v8RuntimePtr = rand(1, 1000); // Simulate a runtime pointer
    }

    public function executeScript($script) {
        // Simulate a script execution and return a random integer
        return rand(1, 1000);
    }

    public function release() {
        self::$runtimeCounter--;
        $this->v8RuntimePtr = null;
    }

    public static function getActiveRuntimes() {
        return self::$runtimeCounter;
    }
}

$runtime = V8Runtime::createV8Runtime();
$result = $runtime->executeScript('some_script();');
$runtime->release();

echo V8Runtime::getActiveRuntimes();
--EXPECT--
