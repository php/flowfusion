--TEST--
Translated seed (inferredbugs-csharp: resin/9)
--FILE--
<?php
$words = ["baby", "bad", "bank", "box", "dad", "daddy", "dance", "dancing"];
$file = "0.bt";
file_put_contents($file, implode("\n", $words) . "\n");

$data = file_get_contents($file);
$lines = array_values(array_filter(explode("\n", $data), 'strlen'));
sort($lines);

$hasWord = function($w) use ($lines) {
    return in_array($w, $lines, true);
};

$startsWith = function($p) use ($lines) {
    $r = [];
    foreach ($lines as $w) if (strncmp($w, $p, strlen($p)) === 0) $r[] = $w;
    return $r;
};

$near = function($q, $max) use ($lines) {
    $r = [];
    foreach ($lines as $w) {
        $m = 0;
        for ($i = 0, $j = 0, $a = strlen($q), $b = strlen($w); $i < $a || $j < $b; ) {
            if ($i < $a && $j < $b && $q[$i] === $w[$j]) { $i++; $j++; continue; }
            $m++;
            if ($m > $max) break;
            if ($i < $a && ($j >= $b || ($i + 1 < $a && $q[$i + 1] === $w[$j]))) $i++;
            else $j++;
        }
        if ($m <= $max) $r[] = $w;
    }
    return $r;
};

$near("bazy", 1);
$near("bazy", 2);
$startsWith("ba");
$hasWord("baby");
$hasWord("bab");
$hasWord("xxx");
$startsWith("ba");
$near("ba", 1);
$near("ba", 2);
$near("ba", 0);
$near("ba", 6);
?>
--EXPECT--
