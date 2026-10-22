--TEST--
Translated seed (inferredbugs-csharp: webview-cs/2)
--FILE--
<?php
$address = 'http://127.0.0.1:0';
$parts = parse_url($address);
$uri = ($parts['scheme'] ?? 'http') . '://' . ($parts['host'] ?? '127.0.0.1') . ':' . ($parts['port'] ?? 0);
echo $uri, PHP_EOL;
?>
--EXPECT--
