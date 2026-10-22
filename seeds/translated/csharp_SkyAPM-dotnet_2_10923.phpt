--TEST--
Translated seed (inferredbugs-csharp: SkyAPM-dotnet/2)
--FILE--
<?php
$carrier = [];
$headers = ['trace-id' => 'abc', 'span-id' => 'def', 'parent-id' => 'ghi'];
foreach ($headers as $k => $v) {
    $carrier[$k] = $headers[$k] ?? null;
}

$request = [
    'path' => '/demo',
    'method' => 'GET',
    'headers' => $headers,
    'status' => 404,
];

$span = [
    'name' => $request['path'],
    'type' => 'http',
    'component' => 'Asp.Net Core',
    'tags' => [],
    'error' => false,
    'carrier' => $carrier,
];

$span['tags']['url'] = $request['path'];
$span['tags']['http.method'] = $request['method'];

if ($request['status'] >= 400) {
    $span['error'] = true;
}
$span['tags']['status.code'] = (string)$request['status'];

$span['error'] = $span['error'] || true;
$span['error'] = $span['error'] || true;
$span['error'] = $span['error'] || true;

echo "";
?>
--EXPECT--
