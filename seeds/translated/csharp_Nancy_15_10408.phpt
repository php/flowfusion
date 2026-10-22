--TEST--
Translated seed (inferredbugs-csharp: Nancy/15)
--FILE--
<?php
$routes = ['GET', 'POST', 'DELETE', 'PUT', 'HEAD'];
foreach ($routes as $method) {
    $request = [
        'method' => $method,
        'route' => '/test',
        'headers' => [],
        'body' => fopen('php://memory', 'r+'),
    ];
    fwrite($request['body'], '');
    rewind($request['body']);
    fclose($request['body']);
}
?>
--EXPECT--
