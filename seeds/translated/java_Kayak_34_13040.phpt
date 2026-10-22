--TEST--
Translated seed (inferredbugs-java: Kayak/34)
--FILE--
<?php

$dateFormat = "Y/m/d H:i:s";
$io = fopen("php://stdout", "w");

function writeLog($message) {
    global $dateFormat, $io;
    $date = date($dateFormat);
    fwrite($io, "[$date] $message\n");
}

writeLog("EVENT Example event message");
writeLog("INFO Example info message");

fclose($io);
--EXPECT--
