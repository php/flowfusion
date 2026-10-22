--TEST--
Translated seed (inferredbugs-csharp: resin/11)
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
    ""
]), LOCK_EX);

$data = file_get_contents("0.bt");
if ($data === false || $data === '') exit(1);

$set = array_fill_keys($words, true);

$hasWord = function(string $w) use ($set): bool {
    return isset($set[$w]);
};

$startsWith = function(string $p) use ($words): array {
    $r = [];
    foreach ($words as $w) if (str_starts_with($w, $p)) $r[] = $w;
    return $r;
};

$near = function(string $q, int $max) use ($words): array {
    if ($max < 0) return [];
    $r = [];
    foreach ($words as $w) {
        $a = str_split($q);
        $b = str_split($w);
        $m = count($a);
        $n = count($b);
        $dp = range(0, $n);
        for ($i = 1; $i <= $m; $i++) {
            $prev = $dp[0];
            $dp[0] = $i;
            for ($j = 1; $j <= $n; $j++) {
                $tmp = $dp[$j];
                $dp[$j] = min($dp[$j] + 1, $dp[$j - 1] + 1, $prev + (($a[$i - 1] !== $b[$j - 1]) ? 1 : 0));
                $prev = $tmp;
            }
        }
        if ($dp[$n] <= $max) $r[] = $w;
    }
    return $r;
};

$near1 = $near("bazy", 1);
$near2 = $near("bazy", 2);
$pref = $startsWith("ba");

$hasWord("baby");
$hasWord("bab");
$hasWord("xxx");
$startsWith("ba");
$near("ba", 1);
$near("ba", 2);
$near("ba", 0);
$near("ba", 6);
$near("bazy", 3);

echo $data;
?>
--EXPECT--
