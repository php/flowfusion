--TEST--
Translated seed (inferredbugs-java: GlobalWarming/6)
--FILE--
<?php

class ClimateEngine {
    private static $instance;
    private $temperatureMap;

    private function __construct() {
        $this->temperatureMap = [
            'world1' => 5.0,
            'world2' => 0.0
        ];
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new ClimateEngine();
        }
        return self::$instance;
    }

    public function getTemperature($worldName) {
        return $this->temperatureMap[$worldName] ?? null;
    }
}

class SnowForm {
    private $heightMap;

    public function blockFormEvent($blockY, $blockType, $worldName) {
        if ($blockType === 'ICE') {
            $temp = ClimateEngine::getInstance()->getTemperature($worldName);

            if ($temp !== null && $blockY < $this->searchHeightMap($temp)) {
                // Simulate event cancellation
                echo "Event cancelled due to height condition.\n";
            }
        }
    }

    public function setJsonModel($jsonModel) {
        $this->heightMap = json_decode($jsonModel, true);
        if ($this->heightMap === null) {
            $this->heightMap = [];
            echo "Unregistered due to null height map.\n";
        }
    }

    private function searchHeightMap($temp) {
        ksort($this->heightMap);
        foreach ($this->heightMap as $key => $value) {
            if ($key > $temp) {
                return $value;
            }
        }
        return PHP_INT_MAX; // Fallback if no height found
    }
}

$heightMapJson = json_encode([
    0.0 => 5,
    3.0 => 10,
    6.0 => 15
]);

$snowForm = new SnowForm();
$snowForm->setJsonModel($heightMapJson);

// Simulating block form event with different Y coordinates
$snowForm->blockFormEvent(4, 'ICE', 'world1');
$snowForm->blockFormEvent(7, 'ICE', 'world2');
$snowForm->blockFormEvent(2, 'ICE', 'world1');

?>
--EXPECT--
