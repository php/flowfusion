--TEST--
Translated seed (inferredbugs-csharp: firebase-admin-dotnet/1)
--FILE--
<?php
$json = '{"error":{"status":"UNAVAILABLE","message":"Test error message"}}';
$resp = ['status' => 503, 'content' => $json, 'type' => 'application/json'];
$e = json_decode($json, true);
$code = ($e['error']['status'] ?? null) === 'INVALID_ARGUMENT' ? 'InvalidArgument' : 'Unavailable';
$msg = $e['error']['message'] ?? "Unexpected HTTP response with status: 503 (ServiceUnavailable)\n$json";
var_dump($code, $msg, $resp);

$text = 'not json';
$resp = ['status' => 503, 'content' => $text, 'type' => 'text/plain'];
$e = json_decode($text, true);
$code = is_array($e) ? (($e['error']['status'] ?? null) === 'INVALID_ARGUMENT' ? 'InvalidArgument' : 'Unavailable') : 'Unavailable';
$msg = is_array($e) && isset($e['error']['message']) ? $e['error']['message'] : "Unexpected HTTP response with status: 503 (ServiceUnavailable)\n$text";
var_dump($code, $msg, $resp);

$json = '{"error":{"message":"Test error message"}}';
$resp = ['status' => 503, 'content' => $json, 'type' => 'application/json'];
$e = json_decode($json, true);
$code = ($e['error']['status'] ?? null) === 'INVALID_ARGUMENT' ? 'InvalidArgument' : 'Unavailable';
$msg = $e['error']['message'] ?? "Unexpected HTTP response with status: 503 (ServiceUnavailable)\n$json";
var_dump($code, $msg, $resp);

$json = '{"error":{"status":"INVALID_ARGUMENT"}}';
$resp = ['status' => 503, 'content' => $json, 'type' => 'application/json'];
$e = json_decode($json, true);
$code = ($e['error']['status'] ?? null) === 'INVALID_ARGUMENT' ? 'InvalidArgument' : 'Unavailable';
$msg = $e['error']['message'] ?? "Unexpected HTTP response with status: 503 (ServiceUnavailable)\n$json";
var_dump($code, $msg, $resp);

$json = '{}';
$resp = ['status' => 503, 'content' => $json, 'type' => 'application/json'];
$e = json_decode($json, true);
$code = ($e['error']['status'] ?? null) === 'INVALID_ARGUMENT' ? 'InvalidArgument' : 'Unavailable';
$msg = $e['error']['message'] ?? "Unexpected HTTP response with status: 503 (ServiceUnavailable)\n$json";
var_dump($code, $msg, $resp);
?>
--EXPECT--
