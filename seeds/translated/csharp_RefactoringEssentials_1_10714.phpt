--TEST--
Translated seed (inferredbugs-csharp: RefactoringEssentials/1)
--FILE--
<?php
$values = [null, 0, '', false, [], 'x'];
foreach ($values as $v) {
    $r = $v ?? 'fallback';
    if ($r === null) {
        throw new Exception();
    }
}

class C {
    public ?string $p = null;
    public function m(): ?string {
        return $this->p;
    }
}

$c = new C();
$x = $c->m();
if ($x === null) {
    $x = 'fallback';
}

$a = ['k' => null];
$y = $a['k'] ?? 'fallback';
if ($y === null) {
    throw new Exception();
}

echo "ok\n";
?>
--EXPECT--
