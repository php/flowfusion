--TEST--
Translated seed (inferredbugs-csharp: PKHeX-Plugins/2)
--FILE--
<?php
$u = $argv[1] ?? 'data:text/plain,hello';
$c = stream_context_create(['http' => ['method' => 'GET', 'header' => "User-Agent: PKHeX-Auto-Legality-Mod\r\nAccept: application/json\r\n"]]);
echo file_get_contents($u, false, $c);
?>
--EXPECT--
