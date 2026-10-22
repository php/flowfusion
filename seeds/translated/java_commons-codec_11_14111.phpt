--TEST--
Translated seed (inferredbugs-java: commons-codec/11)
--FILE--
<?php

$encodedB64 = "AAAA////";
$decodedBytes = [0, 0, 0, 255, 255, 255];

$ins = fopen('data://text/plain;base64,' . base64_encode($encodedB64), 'rb');
$actualBytes = fread($ins, strlen($encodedB64));
fclose($ins);
$actualArray = array_map('ord', str_split($actualBytes));

if (array_slice($actualArray, 0, 6) === $decodedBytes) {
    exit(0);
} else {
    exit(1);
}
--EXPECT--
