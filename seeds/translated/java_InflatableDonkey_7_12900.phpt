--TEST--
Translated seed (inferredbugs-java: InflatableDonkey/7)
--FILE--
<?php

$fallBackDurationMs = 30 * 60 * 1000;

$fileGroups = json_decode('[{"hostInfo":{"expiry":null}},{"hostInfo":{"expiry":5000}}]', true);
$timestampOffset = 100000;
$timestampTolerance = 100;

foreach ($fileGroups as &$fileGroup) {
    $fileGroup = adjustExpiryTimestamp($fileGroup, $timestampOffset, $fallBackDurationMs, $timestampTolerance);
}

function adjustExpiryTimestamp($fileGroup, $offset, $fallBackDurationMs, $timestampTolerance) {
    if (!isset($fileGroup['hostInfo']['expiry'])) {
        return setExpiryTimestamp($fileGroup, round(microtime(true) * 1000) + $fallBackDurationMs);
    }
    $timestamp = $fileGroup['hostInfo']['expiry'] + $offset;
    if (abs($offset) > $timestampTolerance) {
        echo "-- handleEntityTimestampOffset() - system timestamp out of tolerance: {$offset} ms\n";
    }
    return setExpiryTimestamp($fileGroup, $timestamp);
}

function setExpiryTimestamp($fileGroup, $timestamp) {
    $fileGroup['hostInfo']['expiry'] = $timestamp;
    return $fileGroup;
}
--EXPECT--
