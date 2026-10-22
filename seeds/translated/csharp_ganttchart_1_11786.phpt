--TEST--
Translated seed (inferredbugs-csharp: ganttchart/1)
--FILE--
<?php
$barWidth = 20;
$barSpacing = 26;
$headerHeight = 32;
$tasks = [
    ['name' => 'A', 'start' => 0, 'duration' => 3, 'complete' => 0.5, 'collapsed' => false, 'group' => false, 'ancestors' => []],
    ['name' => 'B', 'start' => 3, 'duration' => 2, 'complete' => 0.25, 'collapsed' => false, 'group' => false, 'ancestors' => []],
    ['name' => 'C', 'start' => 5, 'duration' => 4, 'complete' => 0.75, 'collapsed' => false, 'group' => false, 'ancestors' => []],
];
$rects = [];
$row = 0;
foreach ($tasks as $i => $t) {
    $rects[$i] = [
        'left' => $t['start'] * $barWidth,
        'top' => $row * $barSpacing + $barSpacing + $headerHeight,
        'width' => $t['duration'] * $barWidth,
        'height' => 20,
    ];
    $row++;
}
$selected = [];
$dragSource = 0;
$startX = 40;
$prevX = 73;
$delta = intdiv($prevX - $startX, $barWidth);
$tasks[$dragSource]['start'] += $delta;
$rects[$dragSource]['left'] = $tasks[$dragSource]['start'] * $barWidth;
$mouseY = 58;
$row = (int)(($mouseY - $barSpacing - $headerHeight) / $barSpacing);
if ($row < 0) $row = 0;
$selected[] = $tasks[$row];
$tasks[1]['collapsed'] = !$tasks[1]['collapsed'];
$visible = [];
foreach ($tasks as $t) {
    $collapsed = false;
    foreach ($t['ancestors'] as $a) {
        if (!empty($a['collapsed'])) {
            $collapsed = true;
            break;
        }
    }
    if (!$collapsed) $visible[] = $t['name'];
}
echo implode(',', $visible), PHP_EOL;
?>
--EXPECT--
