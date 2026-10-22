--TEST--
Translated seed (inferredbugs-csharp: Kuriimu2/20)
--FILE--
<?php
class ToolStrip {
    public array $items = [];
    public int $itemHeight;
    public function __construct(int $itemHeight = 24) {
        $this->itemHeight = $itemHeight;
    }
}
class ToolStripItem {
    public ?ToolStrip $parent = null;
    public int $width = 0;
    public function registerParent(ToolStrip $parent): void {
        $this->parent = $parent;
    }
}
class SplitterToolStripItem extends ToolStripItem {
    public function __construct() {
        $this->width = 11;
    }
}
class ButtonToolStripItem extends ToolStripItem {
    public bool $hovering = false;
    public bool $clicked = false;
    public function __construct() {
        $this->width = 24;
    }
}
$toolStrip = new ToolStrip();
$items = [];
$item1 = new SplitterToolStripItem();
$item2 = new ButtonToolStripItem();
$items[] = $item1;
$items[] = $item2;
foreach ($items as $item) {
    $item->registerParent($toolStrip);
}
$toolStrip->items = $items;
$items[0] = new ButtonToolStripItem();
$items[1]->hovering = true;
$items[1]->clicked = false;
$toolStrip->items = array_values($items);
echo count($toolStrip->items), PHP_EOL;
?>
--EXPECT--
