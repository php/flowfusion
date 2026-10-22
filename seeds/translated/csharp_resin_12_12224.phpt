--TEST--
Translated seed (inferredbugs-csharp: resin/12)
--FILE--
<?php
$words = ["baby", "bad", "bank", "box", "dad", "daddy", "dance", "dancing"];
$file = "0.bt";
file_put_contents($file, implode("\n", $words) . "\n");

$data = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
sort($data, SORT_STRING);

$hasWord = function(string $w) use ($data): bool {
    return in_array($w, $data, true);
};

$startsWith = function(string $p) use ($data): array {
    return array_values(array_filter($data, fn($w) => str_starts_with($w, $p)));
};

$near = function(string $q, int $max) use ($data): array {
    $out = [];
    foreach ($data as $w) {
        $a = str_split($q);
        $b = str_split($w);
        $m = count($a);
        $n = count($b);
        $dp = array_fill(0, $m + 1, array_fill(0, $n + 1, 0));
        for ($i = 0; $i <= $m; $i++) $dp[$i][0] = $i;
        for ($j = 0; $j <= $n; $j++) $dp[0][$j] = $j;
        for ($i = 1; $i <= $m; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                $c = ($a[$i - 1] === $b[$j - 1]) ? 0 : 1;
                $dp[$i][$j] = min($dp[$i - 1][$j] + 1, $dp[$i][$j - 1] + 1, $dp[$i - 1][$j - 1] + $c);
            }
        }
        if ($dp[$m][$n] <= $max) $out[] = $w;
    }
    return $out;
};

$near1 = $near("bazy", 1);
$near2 = $near("bazy", 2);
$pref = $startsWith("ba");
$exact1 = $hasWord("baby");
$exact2 = $hasWord("bab");
$serialized = file_get_contents($file);

if (count($near1) !== 1 || !in_array("baby", $near1, true)) exit(1);
if (count($near2) !== 3 || !in_array("baby", $near2, true) || !in_array("bank", $near2, true) || !in_array("bad", $near2, true)) exit(1);
if (count($pref) !== 3 || !in_array("baby", $pref, true) || !in_array("bad", $pref, true) || !in_array("bank", $pref, true)) exit(1);
if (!$exact1 || $exact2) exit(1);
if ($serialized === '') exit(1);
?>
--EXPECT--
