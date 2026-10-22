--TEST--
Translated seed (inferredbugs-csharp: resin/6)
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
    public function near(string $p, int $d): array {
        $r = [];
        foreach ($this->words as $w => $_) if (levenshtein($p, $w) <= $d) $r[] = $w;
        return $r;
    }
};

$tree->near("ba", 1);
$tree->startsWith("ba");
$tree->hasWord("xxx");

$tree->add("bad");
$tree->near("ba", 1);
$tree->startsWith("ba");

$tree->add("baby");
$tree->near("ba", 1);
$tree->near("ba", 2);
$tree->startsWith("ba");

$tree->add("xxx");
$tree->hasWord("xxx");
$tree->hasWord("baby");
$tree->hasWord("dad");

$tree->add("dad");
$tree->hasWord("xxx");
$tree->hasWord("baby");
$tree->hasWord("dad");

$tree->add("baby");
$tree->add("dad");
$tree->add("bad");
$tree->hasWord("baby");
$tree->hasWord("bad");
?>
--EXPECT--
