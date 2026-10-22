--TEST--
Translated seed (inferredbugs-csharp: QuestPDF/8)
--FILE--
<?php
$cases = [
    [[500, 5, 0], [[500, 5, 0], [500, 4, 0], [500, 3, 0], [500, 6, 0]]],
    [[500, 6, 0], [[500, 6, 0], [500, 7, 0], [500, 8, 0], [500, 5, 0]]],
    [[500, 5, 1], [[500, 5, 1], [500, 5, 2], [500, 5, 0]]],
    [[500, 5, 2], [[500, 5, 2], [500, 5, 1], [500, 5, 0]]],
    [[500, 5, 0], [[500, 5, 0], [500, 5, 2], [500, 5, 1]]],
    [[300, 5, 0], [[300, 5, 0], [200, 5, 0], [100, 5, 0], [400, 5, 0]]],
    [[400, 5, 0], [[500, 5, 0], [300, 5, 0], [100, 5, 0], [600, 5, 0]]],
    [[600, 5, 0], [[600, 5, 0], [700, 5, 0], [800, 5, 0], [500, 5, 0]]],
    [[500, 5, 0], [[600, 5, 1], [600, 6, 0], [500, 6, 1]]],
];

foreach ($cases as [$target, $styles]) {
    for ($i = 0; $i < count($styles) - 1; $i++) {
        [$tw, $td, $ts] = $target;
        [$aw, $ad, $as] = $styles[$i];
        [$bw, $bd, $bs] = $styles[$i + 1];

        $da = abs($aw - $tw) + abs($ad - $td) + ($as !== $ts ? 10 : 0);
        $db = abs($bw - $tw) + abs($bd - $td) + ($bs !== $ts ? 10 : 0);
        if ($da > $db || ($da === $db && ($aw <=> $bw) > 0)) {
            $tmp = $styles[$i];
            $styles[$i] = $styles[$i + 1];
            $styles[$i + 1] = $tmp;
        }

        [$aw, $ad, $as] = $styles[$i];
        [$bw, $bd, $bs] = $styles[$i + 1];
        $da = abs($aw - $tw) + abs($ad - $td) + ($as !== $ts ? 10 : 0);
        $db = abs($bw - $tw) + abs($bd - $td) + ($bs !== $ts ? 10 : 0);
        if ($da < $db || ($da === $db && ($aw <=> $bw) < 0)) {
            $tmp = $styles[$i];
            $styles[$i] = $styles[$i + 1];
            $styles[$i + 1] = $tmp;
        }
    }
}
?>
--EXPECT--
