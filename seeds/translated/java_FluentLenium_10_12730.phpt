--TEST--
Translated seed (inferredbugs-java: FluentLenium/10)
--FILE--
<?php

class SpringTestNGAdapter {
    private static $parametersThreadLocal;
    private static $testMethodName;
    private static $testClass;

    public function __construct() {
        self::$parametersThreadLocal = [];
    }

    public function starting($testClass, $testName) {
        self::$parametersThreadLocal = $this->getEffectiveParameters($testClass, $testName);
        $sharedWebDriver = $this->getSharedWebDriver(self::$parametersThreadLocal);

        $this->initFluent($sharedWebDriver);
    }

    private function getEffectiveParameters($testClass, $testName) {
        return [
            'testClass' => $testClass,
            'testName' => $testName
        ];
    }

    private function getSharedWebDriver($parameters) {
        return $parameters['testClass'] . ': ' . $parameters['testName'];
    }

    private function initFluent($sharedWebDriver) {
        if (is_null($sharedWebDriver)) {
            $this->releaseFluent();
            return;
        }

        if (isset(self::$parametersThreadLocal['driver'])) {
            if (self::$parametersThreadLocal['driver'] === $sharedWebDriver) {
                return;
            }
            throw new Exception("Trying to init a WebDriver, but another one is still running");
        }

        self::$parametersThreadLocal['driver'] = $sharedWebDriver;
    }

    private function releaseFluent() {
        if (isset(self::$parametersThreadLocal['driver'])) {
            unset(self::$parametersThreadLocal['driver']);
        }
    }

    public function finished($testClass, $testName) {
        $this->releaseFluent();
    }

    public function failed($e, $testClass, $testName) {
        // Handle failure, e.g. log or take action
    }
}

function main() {
    $adapter = new SpringTestNGAdapter();
    $adapter->starting('TestClass', 'testMethod');
    $adapter->finished('TestClass', 'testMethod');
}

main();
--EXPECT--
