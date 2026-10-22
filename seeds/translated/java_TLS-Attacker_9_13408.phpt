--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/9)
--FILE--
<?php

$modificationCount = 7;
$maxModificationValue = 320000;
$maxModificationShiftValue = 50;

function createRandomModification() {
    global $modificationCount, $maxModificationValue, $maxModificationShiftValue;
    $r = rand(0, $modificationCount - 1);
    $modification = rand(0, $maxModificationValue);
    $shiftModification = rand(0, $maxModificationShiftValue);
    
    switch ($r) {
        case 0:
            return $modification; // Addition
        case 1:
            return -$modification; // Subtraction
        case 2:
            return $modification ^ $modification; // XOR
        case 3:
            return $modification; // Explicit value (identity)
        case 4:
            return $modification << $shiftModification; // Shift left
        case 5:
            return $modification >> $shiftModification; // Shift right
        case 6:
            return $modification; // Fallback explicit value
    }
}

$result = createRandomModification();
echo $result;
--EXPECT--
