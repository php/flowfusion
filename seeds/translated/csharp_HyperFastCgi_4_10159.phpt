--TEST--
Translated seed (inferredbugs-csharp: HyperFastCgi/4)
--FILE--
<?php
final class RecordType {
    const BeginRequest = 1;
    const Params = 4;
    const StandardInput = 5;
    const Data = 8;
    const GetValues = 9;
    const AbortRequest = 2;
    const StandardOutput = 6;
    const EndRequest = 3;
}

$requests = [];
$listenerTag = 1;
$requestId = 65535;
$hash = (($requestId << 32) ^ $listenerTag) & 0xFFFFFFFFFFFFFFFF;

$header = [1, RecordType::BeginRequest, 255, 255, 0, 8, 0];
$recordBody = chr(0) . chr(1) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(1);

$type = $header[1];
$reqId = (($header[2] << 8) + $header[3]) & 0xFFFF;

if (!isset($requests[$hash]) && $type === RecordType::BeginRequest) {
    $flags = ord($recordBody[1]);
    $requests[$hash] = [
        'RequestId' => $reqId,
        'Hash' => $hash,
        'fd' => $listenerTag,
        'KeepAlive' => (($flags & 1) === 1),
        'Host' => 0,
        'RequestNumber' => 1
    ];
}

$header = [1, RecordType::Params, 255, 255, 0, 0, 0];
$type = $header[1];
$reqId = (($header[2] << 8) + $header[3]) & 0xFFFF;
$hash = (($reqId << 32) ^ $listenerTag) & 0xFFFFFFFFFFFFFFFF;

if (isset($requests[$hash]) && $type === RecordType::Params) {
    $req = &$requests[$hash];
    if ($req['Host'] === 0) {
        $req['Host'] = $listenerTag;
    }
}

$body = str_repeat("A", 70000);
$length = strlen($body);
if ($length > 65535) {
    $index = 0;
    while ($index < $length) {
        $chunk = min($length - $index, 65507);
        $part = substr($body, $index, $chunk);
        $index += $chunk;
    }
}

$header = [1, RecordType::StandardInput, 255, 255, 0, 0, 0];
$type = $header[1];
$reqId = (($header[2] << 8) + $header[3]) & 0xFFFF;
$hash = (($reqId << 32) ^ $listenerTag) & 0xFFFFFFFFFFFFFFFF;

if (isset($requests[$hash]) && $type === RecordType::StandardInput) {
    $final = (($header[4] << 8) + $header[5]) === 0;
    if ($final) {
        unset($requests[$hash]);
    }
}

echo "ok\n";
--EXPECT--
