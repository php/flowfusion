--TEST--
Translated seed (inferredbugs-csharp: net-ipfs-http-client/1)
--FILE--
<?php
$base = 'data:text/plain,';
$payload = rawurlencode("0.3.8-dev");
echo file_get_contents($base . $payload);
?>
--EXPECT--
