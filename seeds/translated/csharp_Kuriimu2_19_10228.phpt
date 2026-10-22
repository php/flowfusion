--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/19)
--FILE--
<?php
class ToolStripItem {
    public float $width;
    public bool $hover = false;
    public bool $clicked = false;
    public bool $enabled = true;
    public function __construct(float $width) { $this->width = $width; }
}

$items = [new ToolStripItem(24), new ToolStripItem(1), new ToolStripItem(24)];
$spacing = 2;
$paddingLeft = 3;
$paddingTop = 3;

$positions = [];
$x = $paddingLeft;
foreach ($items as $item) {
    $positions[] = [$x, $x + $item->width];
    $x += $item->width + $spacing;
}

$hovered = -1;
$events = [
    [4, 'move'],
    [4, 'down'],
    [4, 'up'],
    [29, 'move'],
    [29, 'down'],
    [29, 'up'],
    [100, 'move'],
];

foreach ($events as [$mx, $type]) {
    $current = -1;
    foreach ($positions as $i => [$l, $r]) {
        if ($mx >= $l && $mx < $r) { $current = $i; break; }
    }
    if ($type === 'move') {
        if ($hovered === $current) {
            if ($hovered >= 0) $items[$hovered]->hover = true;
        } else {
            if ($hovered >= 0) { $items[$hovered]->hover = false; $items[$hovered]->clicked = false; }
            if ($current >= 0) $items[$current]->hover = true;
            $hovered = $current;
        }
    } elseif ($type === 'down') {
        if ($hovered >= 0) $items[$hovered]->clicked = true;
    } elseif ($type === 'up') {
        if ($hovered >= 0) { $items[$hovered]->clicked = false; $items[$hovered]->enabled = $items[$hovered]->enabled; }
    }
}

echo count($items), "\n";
?>
--EXPECT--
