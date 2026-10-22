--TEST--
Translated seed (inferredbugs-java: GlobalWarming/5)
--FILE--
<?php

class ClimateEngine {
    private static $instances = [];

    public static function getInstance($worldName) {
        if (!isset(self::$instances[$worldName])) {
            self::$instances[$worldName] = new self();
        }
        return self::$instances[$worldName];
    }

    public function getTemperature() {
        return mt_rand(-10, 30) + mt_rand() / mt_getrandmax(); // Simulate a temperature between -10 and 30
    }
}

class IceForm {
    private $heightMap;

    public function blockFormEvent($blockY, $blockType) {
        if ($blockType === 'SNOW') {
            $temp = ClimateEngine::getInstance('world')->getTemperature();

            if ($blockY < $this->searchHeightMap($temp)) {
                // Simulating cancellation of event
                return true;
            }
        }
        return false;
    }

    public function setJsonModel($jsonModel) {
        $this->heightMap = json_decode($jsonModel, true);
        if ($this->heightMap === null) {
            // unregister logic can be added here if needed
        }
    }

    private function searchHeightMap($temp) {
        $keys = array_keys($this->heightMap);
        sort($keys);
        foreach ($keys as $key) {
            if ($temp < $key) {
                return $this->heightMap[$key];
            }
        }
        return PHP_INT_MAX; // Return a max value if no suitable key is found
    }
}

$iceForm = new IceForm();
$iceForm->setJsonModel(json_encode([0 => 100, 10 => 90, 20 => 80, 30 => 70]));

$blockY = 85; // Example block Y position
$blockType = 'SNOW'; // Simulating block type
$iceForm->blockFormEvent($blockY, $blockType);
--EXPECT--
