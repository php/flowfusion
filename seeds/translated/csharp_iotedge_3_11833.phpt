--TEST--
Translated seed (inferredbugs-csharp: iotedge/3)
--FILE--
<?php
$threshold = 25;
$env = getenv('TemperatureThreshold');
if ($env !== false && trim($env) !== '' && filter_var($env, FILTER_VALIDATE_INT) !== false) {
    $threshold = (int)$env;
}
$json = getenv('MESSAGE_JSON');
if ($json === false || $json === '') {
    $json = '{"Machine":{"Temperature":30},"Properties":{"Source":"sensor1"}}';
}
$message = json_decode($json, true);
if (is_array($message) && isset($message['Machine']['Temperature']) && $message['Machine']['Temperature'] > $threshold) {
    $filtered = $message;
    $filtered['Properties'] = isset($message['Properties']) && is_array($message['Properties']) ? $message['Properties'] : [];
    $filtered['Properties']['MessageType'] = 'Alert';
    echo json_encode($filtered, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
} else {
    echo $json . PHP_EOL;
}
?>
--EXPECT--
