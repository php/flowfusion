--TEST--
Translated seed (inferredbugs-csharp: QuestPDF/5)
--FILE--
<?php
$cases = [
    [500, 5, 'upright', [[500, 5, 'upright'], [500, 4, 'upright'], [500, 3, 'upright'], [500, 6, 'upright']]],
    [500, 6, 'upright', [[500, 6, 'upright'], [500, 7, 'upright'], [500, 8, 'upright'], [500, 5, 'upright']]],
    [500, 5, 'italic', [[500, 5, 'italic'], [500, 5, 'oblique'], [500, 5, 'upright']]],
    [500, 5, 'oblique', [[500, 5, 'oblique'], [500, 5, 'italic'], [500, 5, 'upright']]],
    [500, 5, 'upright', [[500, 5, 'upright'], [500, 5, 'oblique'], [500, 5, 'italic']]],
    [300, 5, 'upright', [[300, 5, 'upright'], [200, 5, 'upright'], [100, 5, 'upright'], [400, 5, 'upright']]],
    [400, 5, 'upright', [[500, 5, 'upright'], [300, 5, 'upright'], [100, 5, 'upright'], [600, 5, 'upright']]],
    [600, 5, 'upright', [[600, 5, 'upright'], [700, 5, 'upright'], [800, 5, 'upright'], [500, 5, 'upright']]],
    [500, 5, 'upright', [[600, 5, 'italic'], [600, 6, 'upright'], [500, 6, 'italic']]],
];

foreach ($cases as [$tw, $td, $ts, $styles]) {
    for ($i = 0, $n = count($styles) - 1; $i < $n; $i++) {
        [$w1, $d1, $s1] = $styles[$i];
        [$w2, $d2, $s2] = $styles[$i + 1];

        $score1 = abs($tw - $w1) * 100 + abs($td - $d1) * 10 + (($ts === $s1) ? 0 : (($ts === 'upright' || $s1 === 'upright') ? 2 : (($ts === 'italic' && $s1 === 'oblique') || ($ts === 'oblique' && $s1 === 'italic') ? 1 : 3)));
        $score2 = abs($tw - $w2) * 100 + abs($td - $d2) * 10 + (($ts === $s2) ? 0 : (($ts === 'upright' || $s2 === 'upright') ? 2 : (($ts === 'italic' && $s2 === 'oblique') || ($ts === 'oblique' && $s2 === 'italic') ? 1 : 3)));

        if ($score1 > $score2) {
            [$w1, $d1, $s1, $w2, $d2, $s2] = [$w2, $d2, $s2, $w1, $d1, $s1];
        }
    }
}
?>
--EXPECT--
