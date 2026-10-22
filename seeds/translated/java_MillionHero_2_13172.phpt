--TEST--
Translated seed (inferredbugs-java: MillionHero/2)
--FILE--
<?php
$adbPath = "D:\\software\\Android\\android-sdk\\platform-tools\\adb";
$heroPath = "D:\\Photo";

$curDate = date("YmdHis");
$curPhoto = $heroPath . DIRECTORY_SEPARATOR . $curDate . ".png";

while (!file_exists($curPhoto)) {
    shell_exec("$adbPath shell /system/bin/screencap -p /sdcard/screenshot.png");
    usleep(700000);
    shell_exec("$adbPath pull /sdcard/screenshot.png \"$curPhoto\"");
    usleep(200000);
}
--EXPECT--
