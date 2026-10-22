--TEST--
Translated seed (inferredbugs-csharp: net-ipfs-http-client/9)
--FILE--
<?php
$apiAddress = 'http://localhost:5001';
$userAgent = 'net-ipfs/0.0';
$command = 'version';
$arg = "a b+c&d?e/f";
$url = $apiAddress . '/api/v0/' . $command . '?arg=' . rawurlencode($arg);
echo $url, "\n";
echo $userAgent, "\n";
$json = '{"ok":true,"n":1,"s":"x"}';
$data = json_decode($json, true);
echo $data['ok'] ? "1\n" : "0\n";
echo $data['n'] + strlen($data['s']), "\n";
?>
--EXPECT--
