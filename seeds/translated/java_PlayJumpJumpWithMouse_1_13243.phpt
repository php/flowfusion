--TEST--
Translated seed (inferredbugs-java: PlayJumpJumpWithMouse/1)
--FILE--
<?php

$adbPath = 'your_adb_path_here';
$screenshotLocation = 'your_screenshot_location_here';
$error = null;

function longPress($timeMilli, $width, $height) {
    global $adbPath;
    $command = "$adbPath shell input touchscreen swipe $width $height $width $height $timeMilli";
    shell_exec($command);
}

function printScreen() {
    global $adbPath, $screenshotLocation, $error;
    if ($error !== null && $error) {
        printScreenWithOld();
    } else {
        $os = strtolower(PHP_OS);
        if (strpos($os, 'win') === 0) {
            $command = "cmd /c $adbPath exec-out screencap -p > $screenshotLocation";
        } else {
            $command = "bash -c '$adbPath exec-out screencap -p > $screenshotLocation'";
        }
        $output = shell_exec($command);
        if ($output === null) {
            $error = true;
            printScreenWithOld();
        } else {
            checkScreenSuccess();
        }
    }
}

function checkScreenSuccess() {
    global $screenshotLocation;
    if (!file_exists($screenshotLocation)) {
        throw new Exception("Can't read file \"$screenshotLocation\" into image object");
    }
}

function printScreenWithOld() {
    global $adbPath, $screenshotLocation;
    shell_exec("$adbPath shell screencap -p /sdcard/screenshot.png");
    shell_exec("$adbPath pull /sdcard/screenshot.png $screenshotLocation");
}

function getSize() {
    $output = shell_exec("adb shell wm density");
    $splis = explode(" ", trim($output));
    return (int) end($splis);
}

echo getSize();
--EXPECT--
