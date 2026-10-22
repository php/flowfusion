--TEST--
Translated seed (inferredbugs-java: blynk-server/8)
--FILE--
<?php

$reportingFolder = sys_get_temp_dir() . '/reporting';

function createReportingFolder($path) {
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

function generateFilename($id, $pinType, $pin, $graphType) {
    return "{$id}_{$pinType}_{$pin}_{$graphType}.dat";
}

function getAllFromDisk($folder, $id, $pinType, $pin, $count, $graphType) {
    $filename = "$folder/" . generateFilename($id, $pinType, $pin, $graphType);
    if (!file_exists($filename)) {
        return null;
    }
    return file_get_contents($filename);
}

$map = [];
$ts = strtotime("Aug 10, 2015 12:10:56") / 3600;

$aggregationKey1 = ["test", 1, 1, $ts];
$aggregationValue1 = 100;
$aggregationKey2 = ["test", 1, 1, $ts - 1];
$aggregationValue2 = 150.54;
$aggregationKey3 = ["test2", 2, 2, $ts];
$aggregationValue3 = 200;

$map[serialize($aggregationKey1)] = $aggregationValue1;
$map[serialize($aggregationKey2)] = $aggregationValue2;
$map[serialize($aggregationKey3)] = $aggregationValue3;

createReportingFolder("$reportingFolder/test");
createReportingFolder("$reportingFolder/test2");

file_put_contents("$reportingFolder/test/" . generateFilename(1, 1, 1, "HOURLY"), pack('d', $aggregationValue2) . pack('P', ($ts - 1) * 3600));
file_put_contents("$reportingFolder/test/" . generateFilename(1, 1, 1, "HOURLY"), pack('d', $aggregationValue1) . pack('P', $ts * 3600));
file_put_contents("$reportingFolder/test2/" . generateFilename(2, 2, 2, "HOURLY"), pack('d', $aggregationValue3) . pack('P', $ts * 3600));

$data = getAllFromDisk($reportingFolder, 1, 1, 1, 2, "HOURLY");
$byteBuffer = array_values(unpack('d*', $data));
if ($byteBuffer) {
    var_dump($byteBuffer);
}

$data2 = getAllFromDisk($reportingFolder, 2, 2, 2, 1, "HOURLY");
$byteBuffer2 = array_values(unpack('d*', $data2));
if ($byteBuffer2) {
    var_dump($byteBuffer2);
}
--EXPECT--
