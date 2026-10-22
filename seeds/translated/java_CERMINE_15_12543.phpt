--TEST--
Translated seed (inferredbugs-java: CERMINE/15)
--FILE--
<?php

class SVMClassifier {
    protected static $DEFAULT_PARAMETER;

    const C_SVC = 0;
    const POLY = 1;

    public static function init() {
        self::$DEFAULT_PARAMETER = [
            'svm_type' => self::C_SVC,
            'C' => 8,
            'kernel_type' => self::POLY,
            'degree' => 3,
            'gamma' => 1.0 / 8.0,
            'coef0' => 0.5,
            'nu' => 0.5,
            'cache_size' => 100,
            'eps' => 1e-3,
            'p' => 0.1,
            'shrinking' => 1,
            'probability' => 0,
            'nr_weight' => 0,
            'weight_label' => [],
            'weight' => []
        ];
    }

    public static function getDefaultParam() {
        return self::$DEFAULT_PARAMETER;
    }

    public function __construct() {
        $this->param = self::getDefaultParam();
    }

    public function simulateOverflow() {
        $largeNumber = PHP_INT_MAX;
        $result = $largeNumber + 1;
        echo $result; // This would demonstrate overflow in a different context
    }
}

SVMClassifier::init();
$classifier = new SVMClassifier();
$classifier->simulateOverflow();
--EXPECT--
