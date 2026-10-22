--TEST--
Translated seed (inferredbugs-csharp: resin/7)
--FILE--
<?php
$tree = new class {
    public array $words = [];
    public function add(string $w): void { $this->words[$w] = true; }
    public function hasWord(string $w): bool { return isset($this->words[$w]); }
    public function startsWith(string $p): array {
        $r = [];
        foreach ($this->words as $w => $_) if (str_starts_with($w, $p)) $r[] = $w;
        return $r;
    }
    public function near(string $p, int $n): array {
        $r = [];
        foreach ($this->words as $w => $_) if (strncmp($w, $p, strlen($p)) === 0 && strlen($w) <= strlen($p) + $n) $r[] = $w;
        return $r;
    }
};

$tree->near("ba", 1);
$tree->startsWith("ba");
$tree->hasWord("xxx");

$tree->add("bad");
$tree->near("ba", 1);
$tree->startsWith("ba");
$tree->hasWord("xxx");

$tree->add("baby");
$tree->near("ba", 1);
$tree->near("ba", 2);
$tree->startsWith("ba");
$tree->hasWord("baby");

$tree->add("xxx");
$tree->hasWord("xxx");
$tree->hasWord("baby");
$tree->hasWord("dad");

$tree->add("dad");
$tree->hasWord("xxx");
$tree->hasWord("baby");
$tree->hasWord("dad");

$tree->add("bad");
$tree->add("baby");
$tree->startsWith("ba");
$tree->near("ba", 2);
$tree->hasWord("bad");
$tree->hasWord("baby");
?>
--EXPECT--
