--TEST--
Translated seed (inferredbugs-csharp: net-ipfs-http-client/8)
--FILE--
<?php
$apiAddress = 'http://localhost:5001';
$userAgent = 'net-ipfs/0.0';
$command = 'version';
$arg = "a b+c/%?\0";
$url = $apiAddress . '/api/v0/' . $command . '?arg=' . rawurlencode($arg);
echo $userAgent . "\n" . $url . "\n";
?>
--EXPECT--
