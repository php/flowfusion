--TEST--
Translated seed (inferredbugs-csharp: resin/13)
--FILE--
<?php
$words = ["baby", "bad", "bank", "box", "dad", "daddy", "dance", "dancing"];
file_put_contents("0.bt", implode("\r\n", [
    "d1100",
    "a0101",
    "n1102",
    "c0103",
    "i1104",
    "n0105",
    "g0016",
    "e0014",
    "d0112",
    "d0103",
    "y0014",
    "b0100",
    "o1101",
    "x0012",
    "a0101",
    "n1102",
    "k0013",
    "d1012",
    "b0102",
    "y0013",
]) . "\r\n");
$data = file_get_contents("0.bt");
$needles = ["bazy", "ba", "baby", "bab", "xxx", "dad"];
foreach ($needles as $s) {
    similar_text($data, $s, $p);
    $p = (int)$p;
    $x = strpos($data, $s);
    $y = str_contains($data, $s);
}
unlink("0.bt");
?>
--EXPECT--
