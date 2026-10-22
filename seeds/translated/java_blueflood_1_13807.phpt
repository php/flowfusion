--TEST--
Translated seed (inferredbugs-java: blueflood/1)
--FILE--
<?php

class GaugeRollup {
    private $value;
    public $numSamples;

    public function withGauge($gauge) {
        $this->value = $gauge;
        return $this;
    }

    public function equals($obj) {
        return $obj instanceof GaugeRollup && $this->value === $obj->value;
    }

    public static function buildFromGaugeRollups($input) {
        $numSamples = 0;
        $latest = null;
        foreach ($input as $point) {
            if ($latest === null || $latest['timestamp'] < $point['timestamp']) {
                $latest = $point;
            }
            $numSamples += $point['data']['numSamples'];
        }
        $newGauge = (new GaugeRollup())->withGauge($latest['data']['value']);
        $newGauge->numSamples = $numSamples;
        return $newGauge;
    }
}

$points = [
    ['timestamp' => 1, 'data' => ['value' => 10, 'numSamples' => 5]],
    ['timestamp' => 2, 'data' => ['value' => 20, 'numSamples' => 3]],
    ['timestamp' => 3, 'data' => ['value' => 15, 'numSamples' => 4]],
];

GaugeRollup::buildFromGaugeRollups($points);
--EXPECT--
