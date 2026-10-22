--TEST--
Translated seed (inferredbugs-csharp: Nancy/32)
--FILE--
<?php
$routes = ['GET', 'POST', 'DELETE', 'PUT', 'HEAD'];
foreach ($routes as $method) {
    $request = [
        'method' => $method,
        'route' => '/test/' . strtolower($method),
        'headers' => [],
        'body' => fopen('php://memory', 'r+'),
    ];
    fwrite($request['body'], '');
    rewind($request['body']);
    stream_get_contents($request['body']);
    fclose($request['body']);
}
?>
--EXPECT--
