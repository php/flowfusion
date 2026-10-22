--TEST--
Translated seed (inferredbugs-csharp: Nancy/12)
--FILE--
<?php
$methods = ['GET', 'POST', 'DELETE', 'PUT', 'HEAD'];
$routes = ['/', '/a', '/foo/bar', '/%2F', '/x?y=z'];

foreach ($methods as $m) {
    foreach ($routes as $r) {
        $req = [
            'method' => $m,
            'route' => $r,
            'headers' => [],
            'body' => fopen('php://memory', 'r+'),
        ];
        fwrite($req['body'], '');
        rewind($req['body']);
        fclose($req['body']);
    }
}
?>
--EXPECT--
