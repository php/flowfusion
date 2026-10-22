--TEST--
Translated seed (inferredbugs-csharp: dlr/2)
--FILE--
<?php
$s = "héllo\0";
$t = "héll";
$u = "héllo";
$len = strpos($s, "\0");
if ($len === false) $len = strlen($s);
$part = substr($s, 0, $len);
$hash1 = 5381;
$hash2 = 5381;
$n = strlen($part);
for ($i = 0; $i < $n; $i += 2) {
    $c1 = ord($part[$i]);
    $hash1 = (($hash1 << 5) + $hash1) ^ $c1;
    if ($i + 1 < $n) {
        $c2 = ord($part[$i + 1]);
        $hash2 = (($hash2 << 5) + $hash2) ^ $c2;
    }
}
$hash = $hash1 + ($hash2 * 1566083941);
$idx = strpos($part, "l");
$last = strrpos($part, "l");
$eq1 = ($part === $u);
$eq2 = (substr($part, 0, 4) === $t);
$eq3 = (substr($u, 0, 4) === $t);
echo $part, "\n", $len, "\n", $hash, "\n", $idx, "\n", $last, "\n", ($eq1 ? "1" : "0"), ($eq2 ? "1" : "0"), ($eq3 ? "1" : "0"), "\n";
?>
--EXPECT--
