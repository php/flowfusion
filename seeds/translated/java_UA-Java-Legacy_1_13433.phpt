--TEST--
Translated seed (inferredbugs-java: UA-Java-Legacy/1)
--FILE--
<?php

function putDecimal($bd) {
    $scaleInt = $bd->scale();
    if ($scaleInt > 32767) {
        throw new Exception("Decimal scale overflow Short max value: ".$scaleInt);
    }
    if ($scaleInt < -32768) {
        throw new Exception("Decimal scale underflow Short min value: ".$scaleInt);
    }
    $scale = (int) $scaleInt;
    $scaleBytes = pack('v', $scale);
    $valueBytes = strrev($bd->unscaledValue()->toByteArray());
    $combined = $scaleBytes . $valueBytes;
}

function putDecimalArray($bds) {
    if ($bds === null) {
        return -1;
    }
    $length = count($bds);
    for ($i = 0; $i < $length; $i++) {
        putDecimal($bds[$i]);
    }
}

$bds = [];
for ($i = 0; $i < 100; $i++) {
    $bds[] = new BigDecimal("1.0");
}

putDecimalArray($bds);
--EXPECT--
