--TEST--
Translated seed (inferredbugs-csharp: InlineIL.Fody/2)
--FILE--
<?php
$xs = [];
for ($i = 0; $i < 5; $i++) {
    $xs[] = $i;
}
$refs = [];
foreach ($xs as &$v) {
    $refs[] =& $v;
}
unset($v);
for ($i = 0; $i < 5; $i++) {
    $refs[$i] = $refs[$i] + 1;
}
$sum = 0;
foreach ($xs as $x) {
    $sum += $x;
}
echo $sum, PHP_EOL;
--EXPECT--
