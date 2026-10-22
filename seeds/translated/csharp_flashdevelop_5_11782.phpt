--TEST--
Translated seed (inferredbugs-csharp: flashdevelop/5)
--FILE--
<?php
$vals = ["1.2.3", "1.2.4", "1.2+5", "1.2.3.0", "01.002.3", "1.a.3", "2147483647.0", "2147483648.0"];
for ($i = 0; $i < count($vals); $i++) {
    for ($j = 0; $j < count($vals); $j++) {
        $v1 = str_replace("+", ".", $vals[$i]);
        $v2 = str_replace("+", ".", $vals[$j]);
        $a = explode(".", $v1);
        $b = explode(".", $v2);
        $cmp = 0;
        for ($k = 0; $k < count($a); $k++) {
            if (!array_key_exists($k, $b)) break;
            $t1 = filter_var($a[$k], FILTER_VALIDATE_INT);
            $t2 = filter_var($b[$k], FILTER_VALIDATE_INT);
            if ($t1 === false || $t2 === false) continue;
            if ($t1 > $t2) { $cmp = 1; break; }
            if ($t1 < $t2) { $cmp = -1; break; }
        }
        $s = $cmp < 0 ? -1 : ($cmp > 0 ? 1 : 0);
        if ($s < -1 || $s > 1) exit(1);
    }
}
echo "ok\n";
?>
--EXPECT--
