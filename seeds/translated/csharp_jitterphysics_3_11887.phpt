--TEST--
Translated seed (inferredbugs-csharp: jitterphysics/3)
--FILE--
<?php
$size = 6;
$len = (int)(($size - 1.0) * ($size / 2.0)) + $size;
$m0 = array_fill(0, $len, false);
$m1 = array_fill(0, $len, false);

for ($r = 0; $r < $size; $r++) {
    for ($c = 0; $c < $r; $c++) {
        $i = (int)(($r - 1.0) * ($r / 2.0)) + $c;
        $v = ($r + $c) & 3;
        $m0[$i] = ($v & 1) !== 0;
        $m1[$i] = ($v & 2) !== 0;
    }
}

for ($r = 0; $r < $size; $r++) {
    for ($c = 0; $c < $size; $c++) {
        $rr = $r;
        $cc = $c;
        if ($cc > $rr) { $t = $cc; $cc = $rr; $rr = $t; }
        $i = (int)(($rr - 1.0) * ($rr / 2.0)) + $cc;
        $v = (($m1[$i] ? 1 : 0) * 2) + ($m0[$i] ? 1 : 0);
        $m0[$i] = !($v % 2 == 0);
        $m1[$i] = !($v / 2 == 0);
        $b0 = $m0[$i];
        $b1 = $m1[$i];
        if (!($b0 && $b1)) {
            $b1 = !($b0 ^ $b1);
            $b0 = !$b0;
            $m0[$i] = $b0;
            $m1[$i] = $b1;
        }
    }
}

echo "ok\n";
?>
--EXPECT--
