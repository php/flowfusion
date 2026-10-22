--TEST--
Translated seed (inferredbugs-java: BridJ/1)
--FILE--
<?php

function getNativePeerHandle($frame) {
    return intval($frame); // Simulate native handle retrieval
}

$frame = random_int(1, PHP_INT_MAX); // Simulate window handle
$sizeOfDrawingSurface = 6 * PHP_INT_SIZE; // Simulated size of JAWT_DrawingSurface
$sizeOfRectangle = 4 * 4; // Simulated size of JAWT_Rectangle

// Simulating the checks from the Java version
if (PHP_OS === 'Darwin' || version_compare(PHP_VERSION, '5.6.0', '<')) {
    return; // Skip if conditions met (PHP 5.6 and below are similar to Java checks)
}

echo $sizeOfDrawingSurface . PHP_EOL;
echo $sizeOfRectangle . PHP_EOL;

$nativeHandle = getNativePeerHandle($frame);
if ($nativeHandle === 0) {
    exit(1); // Simulate failure if no native handle returned
}

sleep(1); // Simulate delay
$frame = null; // Simulate closing of the window
exit(0); // Successful termination
?>
--EXPECT--
