--TEST--
Translated seed (inferredbugs-java: commons-imaging/5)
--FILE--
<?php
$input = "Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World! Hello, World!";
$compressed = gzcompress($input);
$decompressed = gzuncompress($compressed);
echo $decompressed;
?>
--EXPECT--
