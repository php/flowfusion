--TEST--
Translated seed (inferredbugs-csharp: TerminalGuiDesigner/17)
--FILE--
<?php
class Tab {}
class TabView {
    public array $tabs = [];
    public function addTab(Tab $tab, bool $select): void { $this->tabs[] = $tab; }
    public function removeTab(Tab $tab): void {
        foreach ($this->tabs as $i => $t) {
            if ($t === $tab) { array_splice($this->tabs, $i, 1); return; }
        }
    }
    public function setNeedsDisplay(): void {}
}
class Design {
    public mixed $view;
    public function __construct(mixed $view) { $this->view = $view; }
}
class AddTabOperation {
    private ?Tab $tab = null;
    private TabView $tabView;
    public Design $design;
    public function __construct(Design $design) {
        $this->design = $design;
        if (!($design->view instanceof TabView)) {
            throw new InvalidArgumentException("Design must be for a TabView to support AddTabOperation");
        }
        $this->tabView = $design->view;
    }
    public function do(): bool {
        if ($this->tab !== null) {
            throw new Exception("This command has already been performed once. Use Redo instead of Do");
        }
        $newTabName = "MyTab";
        $this->tabView->addTab($this->tab = new Tab(), true);
        $this->tabView->setNeedsDisplay();
        return true;
    }
    public function redo(): void {
        if ($this->tab === null || in_array($this->tab, $this->tabView->tabs, true)) return;
        $this->tabView->addTab($this->tab, true);
        $this->tabView->setNeedsDisplay();
    }
    public function undo(): void {
        if ($this->tab === null || !in_array($this->tab, $this->tabView->tabs, true)) return;
        $this->tabView->removeTab($this->tab);
        $this->tabView->setNeedsDisplay();
    }
}
$op = new AddTabOperation(new Design(new TabView()));
$op->do();
$op->undo();
$op->redo();
$op->undo();
echo "ok\n";
?>
--EXPECT--
