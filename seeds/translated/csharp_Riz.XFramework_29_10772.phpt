--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/29)
--FILE--
<?php
declare(strict_types=1);

$cases = [
    ['abc', 'b'],
    [null, 'x'],
    ['hello', ''],
];

foreach ($cases as [$a, $b]) {
    $r = ($a ?? $b);
    $s = (string)$r;
    $t = str_contains($s, $b) ? $s : $s . $b;
    $u = substr($t, 0, strlen($t));
    $v = strlen($u);
    $w = $v ? substr($u, 0, $v - 1) . substr($u, $v - 1, 1) : '';
    $x = $w . (string)intval(log(100, 10));
    $y = date('Y-m-d H:i:s', strtotime('+1 day', strtotime('2020-02-28')));
    $z = md5($x . $y);
    echo $z, "\n";
}
?>
--EXPECT--
