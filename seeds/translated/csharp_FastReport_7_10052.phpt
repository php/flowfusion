--TEST--
Translated seed (inferredbugs-csharp: FastReport/7)
--FILE--
<?php
$inputs = ["", "a", "rij", "rijA", "rij" . base64_encode("abc"), str_repeat("x", 15), str_repeat("x", 16), str_repeat("x", 17), random_bytes(31)];
foreach ($inputs as $s) {
    $h = hash("sha256", $s, true);
    $b = substr($h, 0, 3) === "rij" ? $s : "rij" . base64_encode($s);
    $c = substr($b, 0, 3) === "rij" ? base64_decode(substr($b, 3), true) : $b;
    $m = hash("sha256", $c === false ? "" : $c, false);
    $x = unpack("H*", $h . $m);
    $y = pack("H*", $x[1]);
}
echo "ok\n";
?>
--EXPECT--
