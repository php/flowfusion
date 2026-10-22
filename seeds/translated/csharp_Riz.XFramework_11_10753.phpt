--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/11)
--FILE--
<?php
$items = [1, 2, 3, 4, 5];
$idx = -1;
foreach ($items as $i => $v) {
    if (($v & 1) === 0) {
        $idx = $i;
        break;
    }
}
$mapped = array_map(fn($x) => $x * 2, array_filter($items, fn($x) => $x > 2));
$page = intdiv(count($mapped) + 2 - 1, 2);
$nested = [[['id' => 7]]];
$val = $nested[0][0]['id'];
$ref =& $nested[0][0]['id'];
$ref = $val + $page + $idx;
echo $nested[0][0]['id'], "\n";
echo $page, "\n";
echo $idx, "\n";
?>
--EXPECT--
