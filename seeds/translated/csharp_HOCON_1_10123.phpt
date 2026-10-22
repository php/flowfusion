--TEST--
Translated seed (inferredbugs-csharp: HOCON/1)
--FILE--
<?php
$root = [];
$root['a'] = [];
$root['a']['b'] = 1;
$root['a']['b'] = $root['a'];
$root['a']['b']['c'] = 2;
$root['x'] = &$root['a']['b'];
$root['x']['d'] = 3;
$root['y'] = [];
$root['y']['z'] = &$root['y'];
$root['y']['z']['w'] = 4;
$copy = $root;
$copy['a']['b']['e'] = 5;
echo "ok\n";
?>
--EXPECT--
