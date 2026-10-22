--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/28)
--FILE--
<?php
$xs = [null, 0, 1, true, false, "", "0", "1", "x", [1, 2], ["a" => 1], new stdClass()];
foreach ($xs as $a) {
    foreach ($xs as $b) {
        $r = ($a ?? $b) ? (($a && $b) || ($a == $b)) : (($a === $b) ? 1 : 0);
        $s = is_array($a) ? (count($a) ? $a[0] ?? null : null) : (is_object($a) ? (property_exists($a, "p") ? $a->p : null) : $a);
        $t = is_string($s) ? ($s . $b) : ($s + (is_bool($b) ? (int)$b : (is_int($b) ? $b : 0)));
        if (is_array($r) || is_object($t)) {
            $u = (array)$r + (array)$t;
        } else {
            $u = [$r, $t];
        }
        $v = ($u[0] ?? null) ?? ($u[1] ?? null);
        $w = ($v === null) ? ($a ?: $b) : $v;
        $z = (is_numeric($w) ? (int)$w : strlen((string)$w)) % 3;
        echo $z;
    }
}
echo "\n";
--EXPECT--
