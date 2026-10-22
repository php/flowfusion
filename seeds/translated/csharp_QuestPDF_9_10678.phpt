--TEST--
Translated seed (inferredbugs-csharp: QuestPDF/9)
--FILE--
<?php
$target = ['weight' => 500, 'width' => 5, 'slant' => 0];
$styles = [
    ['weight' => 600, 'width' => 5, 'slant' => 2],
    ['weight' => 600, 'width' => 6, 'slant' => 0],
    ['weight' => 500, 'width' => 6, 'slant' => 2],
];

$rankSlant = fn($s) => $s === 0 ? 0 : ($s === 2 ? 1 : 2);
$better = function ($t, $a, $b) use ($rankSlant) {
    $da = abs($a['weight'] - $t['weight']) + abs($a['width'] - $t['width']) + $rankSlant($a['slant']) * 1000;
    $db = abs($b['weight'] - $t['weight']) + abs($b['width'] - $t['width']) + $rankSlant($b['slant']) * 1000;
    return $da < $db || ($da === $db && $a['weight'] >= $b['weight']);
};

for ($i = 0; $i < count($styles) - 1; $i++) {
    $better($target, $styles[$i], $styles[$i + 1]);
    $better($target, $styles[$i + 1], $styles[$i]);
}
?>
--EXPECT--
