--TEST--
Translated seed (inferredbugs-csharp: net-ipfs-http-client/3)
--FILE--
<?php
$u = 'https://example.com';
@file_get_contents($u);
echo "done\n";
?>
--EXPECT--
