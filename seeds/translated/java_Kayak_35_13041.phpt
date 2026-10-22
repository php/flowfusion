--TEST--
Translated seed (inferredbugs-java: Kayak/35)
--FILE--
<?php
$dateFormat = 'Y/m/d H:i:s';
$io = null;

function createOutput() {
    global $io;
    if ($io === null) {
        $io = fopen('php://output', 'w');
    }
}

function printEvent($message) {
    global $io, $dateFormat;
    createOutput();
    flock($io, LOCK_EX);
    $date = date($dateFormat);
    fwrite($io, "[$date] EVENT $message\n");
    fflush($io);
    flock($io, LOCK_UN);
}

function printInfo($info) {
    global $io, $dateFormat;
    createOutput();
    flock($io, LOCK_EX);
    $date = date($dateFormat);
    fwrite($io, "[$date] INFO $info\n");
    fflush($io);
    flock($io, LOCK_UN);
}

printEvent("Sample event message");
printInfo("Sample information message");
--EXPECT--
