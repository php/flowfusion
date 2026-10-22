--TEST--
Translated seed (inferredbugs-csharp: Typin/8)
--FILE--
<?php
class Builder {
    private bool $built = false;
    private array $items = [];
    function add($x) { $this->items[] = $x; return $this; }
    function cfg(callable $f) { $f($this); return $this; }
    function build() {
        if ($this->built) throw new RuntimeException("built once");
        $this->built = true;
        return (object)$this->items;
    }
}

$b = new Builder();
$b->add("cmd")->add("cmd")->add("dir")->add("mw")->add("console")->cfg(function($c) {
    $c->add(str_repeat("t", 2));
    $c->add(str_replace("{{title}}", "{title}", "Startup message {{title}} {title} {version} {executable} {description}"));
});
$a = $b->build();

try {
    $b->build();
} catch (RuntimeException $e) {
}

$b2 = new Builder();
$b2->cfg(function($c) {
    $c->add("cmd")->add("cmd")->add("dir")->add("dir")->add("console");
})->cfg(function($c) {
    $c->add("test")->add("test")->add("test")->add("test");
});
$b2->build();

$b3 = new Builder();
$b3->add(["a" => 1])->add(["a" => 1])->add(["b" => 2])->add(["b" => 2])->add(["c" => 3])->build();

echo "";
?>
--EXPECT--
