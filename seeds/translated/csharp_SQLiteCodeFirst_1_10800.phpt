--TEST--
Translated seed (inferredbugs-csharp: SQLiteCodeFirst/1)
--FILE--
<?php
$data = "The quick brown fox jumps over the lazy dog";
$hash = base64_encode(hash('sha512', $data, true));
echo $hash;
?>
--EXPECT--
