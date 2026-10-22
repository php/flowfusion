--TEST--
Translated seed (inferredbugs-java: PlayJumpJumpWithMouse/3)
--FILE--
<?php

$adbPath = 'adb'; // replace with actual adb path
$screenshotLocation = 'screenshot.png';
$error = null;

function longPress($timeMilli) {
    global $adbPath;
    $width = rand(0, 1000); // Simulate random width, replace with actual logic if needed
    $height = rand(300, 800); // Simulate random height, replace with actual logic if needed
    $command = "$adbPath shell input touchscreen swipe $width $height $width $height " . (int)$timeMilli;
    shell_exec($command);
}

function printScreen() {
    global $error, $adbPath, $screenshotLocation;
    if ($error) {
        printScreenWithOld();
    } else {
        $command = "$adbPath exec-out screencap -p > $screenshotLocation";
        $output = shell_exec($command);
        if ($output === null) {
            $error = true;
            printScreenWithOld();
        }
    }
}

function printScreenWithOld() {
    global $adbPath, $screenshotLocation;
    shell_exec("$adbPath shell screencap -p /sdcard/screenshot.png");
    shell_exec("$adbPath pull /sdcard/screenshot.png $screenshotLocation");
}

function getSize() {
    global $adbPath;
    $output = shell_exec("$adbPath shell wm density");
    $splits = explode(" ", $output);
    return (int)end($splits);
}

longPress(1000);
printScreen();
echo getSize();
--EXPECT--
