--TEST--
Translated seed (inferredbugs-csharp: python-language-server/8)
--FILE--
<?php
$u = new stdClass();
$r = null;
$u->v = 1;
$a = [&$u->v];
$b = [&$u->v];
$r = $a[0];
$a[0] = 2;
$b[0] = 3;
echo $u->v, "\n";
echo $r, "\n";
?>
--EXPECT--
