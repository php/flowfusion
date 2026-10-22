--TEST--
Translated seed (inferredbugs-csharp: fido2-net-lib/3)
--FILE--
<?php
class Fido2VerificationException extends Exception {}

$ecDsaSig = "\x30\x06\x02\x01\x01\x02\x01\x02";
$off = 0;
$len = strlen($ecDsaSig);
if ($len > 0 && $ecDsaSig[$off] === "\x30") {
    $off++;
    $dataLen = ord($ecDsaSig[$off++]);
    if (($dataLen & 0x80) !== 0) {
        $longLenBytes = $dataLen & 0x7f;
        $dataLen = 0;
        for ($i = 0; $i < $longLenBytes; $i++) $dataLen = ($dataLen << 8) | ord($ecDsaSig[$off++]);
    }
    if ($ecDsaSig[$off++] === "\x02") {
        $rLen = ord($ecDsaSig[$off++]);
        if ($rLen > 0 && $ecDsaSig[$off] === "\x00" && (($ecDsaSig[$off + 1] & "\x80") !== "\x00")) {
            $off++;
            $rLen--;
        }
        $r = substr($ecDsaSig, $off, $rLen);
        $off += $rLen;
        if ($ecDsaSig[$off++] === "\x02") {
            $sLen = ord($ecDsaSig[$off++]);
            if ($sLen > 0 && $ecDsaSig[$off] === "\x00" && (($ecDsaSig[$off + 1] & "\x80") !== "\x00")) {
                $off++;
                $sLen--;
            }
            $s = substr($ecDsaSig, $off, $sLen);
            $sig = $r . $s;
        }
    }
}

$authData = str_repeat("\x00", 32) . "\x41" . "\x00\x00\x00\x01";
$offset = 0;
$rpIdHash = substr($authData, $offset, 32); $offset += 32;
$flags = ord($authData[$offset++]);
$signCount = substr($authData, $offset, 4); $offset += 4;
if ($offset !== strlen($authData)) throw new Exception("parse error");
echo bin2hex($rpIdHash) . PHP_EOL;
echo $flags . PHP_EOL;
echo bin2hex($signCount) . PHP_EOL;
echo bin2hex($sig) . PHP_EOL;
?>
--EXPECT--
