--TEST--
Translated seed (inferredbugs-csharp: sdk-csharp/4)
--FILE--
<?php
$values = [null, true, false, 0, 1, -1, 1.5, "x", "", [], [1, 2], ["a" => 1]];
foreach ($values as $v) {
    $j = json_encode($v, JSON_THROW_ON_ERROR);
    $d = json_decode($j, true, 512, JSON_THROW_ON_ERROR);
    if ($d !== $v) {
        if (is_float($v) && is_float($d) && abs($v - $d) < 1e-12) {
            continue;
        }
        if ($v === [] && $d === []) {
            continue;
        }
    }
}

$payloads = [
    ['contentType' => 'application/json', 'data' => ['a' => 1]],
    ['contentType' => 'text/plain', 'data' => "a\nb"],
    ['contentType' => 'application/octet-stream', 'data' => random_bytes(4)],
    ['contentType' => null, 'data' => null],
];

foreach ($payloads as $p) {
    $ct = $p['contentType'] ?? 'application/json';
    $data = $p['data'];
    if ($data === null) {
        $encoded = '';
    } elseif ($ct === 'application/json') {
        $encoded = json_encode($data, JSON_THROW_ON_ERROR);
        $decoded = json_decode($encoded, true, 512, JSON_THROW_ON_ERROR);
    } elseif (str_starts_with($ct, 'text/') && is_string($data)) {
        $encoded = $data;
        $decoded = $encoded;
    } elseif (is_string($data)) {
        $encoded = base64_encode($data);
        $decoded = base64_decode($encoded, true);
    } else {
        $encoded = '';
        $decoded = $data;
    }
}

$structured = json_encode([
    'specversion' => '1.0',
    'id' => '1',
    'source' => '/',
    'type' => 't',
    'datacontenttype' => 'application/json',
    'data' => ['x' => null, 'y' => [1, 2, 3]],
], JSON_THROW_ON_ERROR);
$event = json_decode($structured, true, 512, JSON_THROW_ON_ERROR);
$event['data_base64'] = null;
unset($event['data_base64']);
echo "";
?>
--EXPECT--
