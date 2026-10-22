--TEST--
Translated seed (inferredbugs-csharp: Flurl/2)
--FILE--
<?php
$cookieJar = [];
$cookieJar['z'] = '999';
echo (($cookieJar['z'] ?? null) === '999' ? "1" : "0"), PHP_EOL;
?>
--EXPECT--
