--TEST--
Translated seed (inferredbugs-csharp: sendgrid-csharp/2)
--FILE--
<?php
$h = [];
$h["foo"] = true;
$s = serialize($h);
unserialize($s);
?>
--EXPECT--
