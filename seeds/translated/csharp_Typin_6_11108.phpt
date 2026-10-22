--TEST--
Translated seed (inferredbugs-csharp: Typin/6)
--FILE--
<?php
class Builder {
    private bool $built = false;
    private array $items = [];
    public function add($x) { $this->items[] = $x; return $this; }
    public function build() {
        if ($this->built) throw new Exception("built");
        $this->built = true;
        return (object)$this->items;
    }
}

$b = new Builder();
$b->add("cmd")->add("dir")->add("mw")->add("console")->add("startup");
$app = $b->build();

$b2 = new Builder();
$b2->add("x");
try { $b2->build(); $b2->build(); } catch (Throwable $e) {}

$b3 = new Builder();
$b3->add("cmd")->add("dir")->add("mw")->add("console")->add("interactive")->add("startup");
$app = $b3->build();

$b4 = new Builder();
$b4->add("cmd")->add("dir")->add("console")->add("startup")->add("buffered");
$app = $b4->build();

$b5 = new Builder();
$b5->add("startup");
$app = $b5->build();

$b6 = new Builder();
$b6->add("cfg1")->add("cfg2")->add("cmd")->add("dir")->add("console");
$app = $b6->build();
?>
--EXPECT--
