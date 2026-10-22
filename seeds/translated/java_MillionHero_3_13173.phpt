--TEST--
Translated seed (inferredbugs-java: MillionHero/3)
--FILE--
<?php

$adbPath = "D:\\software\\Android\\android-sdk\\platform-tools\\adb";
$heroPath = "D:\\Photo";

$current = date("YmdHis");
$curPhoto = $heroPath . DIRECTORY_SEPARATOR . $current . ".png";

while (!file_exists($curPhoto)) {
    exec("$adbPath shell /system/bin/screencap -p /sdcard/screenshot.png");
    usleep(700000);
    exec("$adbPath pull /sdcard/screenshot.png $curPhoto");
    usleep(200000);
}

?>
--EXPECT--
