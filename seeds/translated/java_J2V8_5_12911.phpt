--TEST--
Translated seed (inferredbugs-java: J2V8/5)
--FILE--
<?php
$lock = new stdClass();
$runtimeCounter = 0;
$initialized = false;
$v8Flags = null;
$executors = [];

function load($tmpDirectory) {
    // Simulated load of native libraries
}

function createV8Runtime($globalAlias = null, $tempDirectory = null) {
    global $lock, $runtimeCounter, $initialized, $v8Flags;

    if (!$initialized) {
        load($tempDirectory);
        $initialized = true;
    }
    $runtimeCounter++;
    return new V8();
}

class V8 {
    private $locker;
    private $v8RuntimePtr;

    public function __construct() {
        $this->locker = new stdClass();
        $this->v8RuntimePtr = 1; // Simulated pointer
    }

    public function executeIntegerScript($script) {
        return eval("return $script;");
    }
}

$runtime = createV8Runtime();
$result = $runtime->executeIntegerScript("2 + 3");
echo $result;
--EXPECT--
