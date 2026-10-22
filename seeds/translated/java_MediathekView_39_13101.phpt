--TEST--
Translated seed (inferredbugs-java: MediathekView/39)
--FILE--
<?php

$javaVersion = phpversion();
$osVersion = PHP_OS_FAMILY;
$isMac = (stripos(PHP_OS, 'Darwin') === 0);
$isJava6OnMac = $isMac && (strpos($javaVersion, '1.6') === 0);
$is64BitJavaOnMac = $isMac && (PHP_INT_SIZE === 8);
$isLeopard = $isMac && (stripos($osVersion, '10.5') === 0);
--EXPECT--
