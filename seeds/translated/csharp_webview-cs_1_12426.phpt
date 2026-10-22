--TEST--
Translated seed (inferredbugs-csharp: webview-cs/1)
--FILE--
<?php
$address = 'http://127.0.0.1:0';
$parts = parse_url($address);
$uri = $parts['scheme'] . '://' . $parts['host'] . ':' . $parts['port'];
echo $uri;
?>
--EXPECT--
