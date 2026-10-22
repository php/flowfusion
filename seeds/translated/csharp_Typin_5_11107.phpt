--TEST--
Translated seed (inferredbugs-csharp: Typin/5)
--FILE--
<?php
class Builder {
    private bool $built = false;
    private array $cfg = [];
    function add($x) { $this->cfg[] = $x; return $this; }
    function set($k, $v) { $this->cfg[$k] = $v; return $this; }
    function build() {
        if ($this->built) throw new Exception("built");
        $this->built = true;
        return $this->cfg;
    }
}

$b = new Builder();
$b->add('cmd')->add('cmds')->add('dir')->set('title', 'test')->set('console', fopen('php://memory', 'r+'));
$app = $b->build();

try {
    $b->build();
} catch (Throwable $e) {
}

$b = new Builder();
$b->add('cmd')->add('cmds')->add('dir')->set('interactive', true)->set('prompt', 'magenta')->set('console', fopen('php://memory', 'r+'));
$app = $b->build();

$b = new Builder();
$b->add('cmd')->add('cmds')->add('dir')->set('console', fopen('php://memory', 'r+'));
$app = $b->build();

$b = new Builder();
$b->add('cmd')->add('cmds')->add('dir')->set('startup', 'CustomStartupClass');
$app = $b->build();

$b = new Builder();
$b->add('cmd')->add('dir')->set('title', 'test')->set('console', fopen('php://stdout', 'w'));
$app = $b->build();
?>
--EXPECT--
