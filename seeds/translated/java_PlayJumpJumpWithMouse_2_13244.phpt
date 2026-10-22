--TEST--
Translated seed (inferredbugs-java: PlayJumpJumpWithMouse/2)
--FILE--
<?php

$adbPath = "path_to_adb"; // Define the ADB path
$screenshotLocation = "screenshot.png"; // Define the screenshot location
$error = null;

function longPress($timeMilli, $width, $height) {
    global $adbPath;
    $command = "$adbPath shell input touchscreen swipe $width $height $width $height $timeMilli";
    exec($command, $output, $returnVar);
    foreach ($output as $line) {
        echo $line . "\n";
    }
}

function printScreen() {
    global $adbPath, $screenshotLocation, $error;
    if ($error !== null && $error) {
        printScreenWithOld();
    } else {
        $command = "$adbPath exec-out screencap -p > $screenshotLocation";
        exec($command, $output, $returnVar);
        if ($returnVar !== 0) {
            $error = true;
            printScreenWithOld();
        }
    }
}

function printScreenWithOld() {
    global $adbPath, $screenshotLocation;
    exec("$adbPath shell screencap -p /sdcard/screenshot.png");
    exec("$adbPath pull /sdcard/screenshot.png $screenshotLocation");
}

function getSize() {
    $output = [];
    exec("adb shell wm density", $output, $returnVar);
    if ($returnVar === 0) {
        $line = $output[0];
        $splis = explode(" ", $line);
        return (int)$splis[count($splis) - 1];
    }
    return 480;
}

$size = getSize();
echo $size . "\n";

$width = rand(0, 100); // Simulating the selection of a random width
$height = rand(100, 800); // Simulating the selection of a random height
longPress(1000, $width, $height); // Long press for 1000 milliseconds

printScreen(); // Attempt to take a screenshot
--EXPECT--
