--TEST--
Translated seed (inferredbugs-csharp: sdk-csharp/5)
--FILE--
<?php
$data = ['specversion' => '1.0', 'id' => '1', 'source' => '/', 'type' => 't'];

$data['data'] = ['jsonPropertyName' => 'test'];
$json = json_encode($data, JSON_UNESCAPED_SLASHES);
$event = json_decode($json, true);
$model = $event['data'] ?? null;
if (!is_array($model) || ($model['jsonPropertyName'] ?? null) !== 'test') {
    throw new Exception('decode structured failed');
}

$json = json_encode($data, JSON_UNESCAPED_SLASHES);
$event = json_decode($json, true);
if (array_key_exists('data', $event) && !is_null($event['data'])) {
    throw new Exception('no data failed');
}

$data['data_base64'] = base64_encode('{}');
$json = json_encode($data, JSON_UNESCAPED_SLASHES);
$event = json_decode($json, true);
if (array_key_exists('data', $event) && array_key_exists('data_base64', $event)) {
    throw new Exception('base64 conflict not detected');
}

$binary = json_encode(['jsonPropertyName' => 'test'], JSON_UNESCAPED_SLASHES);
$model = json_decode($binary, true);
if (($model['jsonPropertyName'] ?? null) !== 'test') {
    throw new Exception('decode binary failed');
}

$empty = '';
if ($empty !== '') {
    throw new Exception('empty payload failed');
}

$encoded = json_encode(['jsonPropertyName' => 'test'], JSON_UNESCAPED_SLASHES);
$decoded = json_decode($encoded, true);
if (($decoded['jsonPropertyName'] ?? null) !== 'test') {
    throw new Exception('encode binary failed');
}

$wrong = ['text' => 'Wrong type'];
$json = json_encode($wrong, JSON_UNESCAPED_SLASHES);
$decoded = json_decode($json, true);
if (!is_array($decoded) || !array_key_exists('text', $decoded)) {
    throw new Exception('wrong type path failed');
}
?>
--EXPECT--
