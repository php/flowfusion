--TEST--
Translated seed (inferredbugs-csharp: cscs/2)
--FILE--
<?php
$v = [1];
$v[0] =& $v;
$s = 'a';
$s .= $s;
$a = [];
$a['x'] = 1;
$a['y'] =& $a['x'];
$a['y'] += 2;
$b = [[1, 2], ['k' => 3]];
$c = $b[1]['k'] ?? null;
$d = is_array($v) ? count($v) : 0;
$e = gettype($s);
$f = strlen($s);
$g = json_encode($a);
echo $c, "\n", $d, "\n", $e, "\n", $f, "\n", $g, "\n";
?>
--EXPECT--
