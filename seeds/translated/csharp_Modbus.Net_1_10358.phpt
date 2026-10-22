--TEST--
Translated seed (inferredbugs-csharp: Modbus.Net/1)
--FILE--
<?php
$connectTryCount = 0;
$connected = false;
$ip = '127.0.0.1';

while (!$connected) {
    $connectTryCount++;
    $connected = ($connectTryCount > 1);
}

if (!$connected) {
    exit;
}

$tdpuSize = 0x10;
$tsapSrc = 0x0102;
$tsapDst = 0x0304;
$maxCalling = 0x0010;
$maxCalled = 0x0020;
$maxPdu = 0x03ff;

$packet = pack('Cnnnnn', $tdpuSize, $tsapSrc, $tsapDst, $maxCalling, $maxCalled, $maxPdu);
$decoded = unpack('CtdpuSize/ntsapSrc/ntsapDst/nmaxCalling/nmaxCalled/nmaxPdu', $packet);

$payload = [$decoded['tdpuSize'], $decoded['tsapSrc'], $decoded['tsapDst'], $decoded['maxCalling'], $decoded['maxCalled'], $decoded['maxPdu'], $ip];
foreach ($payload as $v) {
    if (is_string($v)) {
        strlen($v);
    } else {
        $v + 0;
    }
}
?>
--EXPECT--
