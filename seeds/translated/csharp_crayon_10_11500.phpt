--TEST--
Translated seed (inferredbugs-csharp: crayon/10)
--FILE--
<?php
class T {
    public string $v;
    function __construct(string $v) { $this->v = $v; }
}
class S {
    private array $t;
    private int $i = 0;
    function __construct(array $t) { $this->t = $t; }
    function peek() { return $this->t[$this->i] ?? null; }
    function next() { return $this->t[$this->i++] ?? null; }
    function isNext(string $v): bool { return (($this->peek()['v'] ?? null) === $v); }
}
function p($s) {
    $x = $s->next();
    if ($x === null) return null;
    $r = $x['v'];
    $n = $s->peek();
    if ($r === '(') {
        $a = p($s);
        if ($s->isNext('?')) {
            $s->next();
            $b = p($s);
            $s->next();
            $c = p($s);
            return ['?:', $a, $b, $c];
        }
        if ($s->isNext('??')) {
            $s->next();
            $b = p($s);
            return ['??', $a, $b];
        }
        return $a;
    }
    if ($r === '!') return ['!', p($s)];
    if ($r === '-') return ['-', p($s)];
    if ($r === '++' || $r === '--') return [$r, p($s), true];
    if ($x['t'] === 'n') return (strpos($r, '.') !== false) ? (float)$r : (int)$r;
    if ($x['t'] === 'w') return $r;
    return $r;
}
$tokens = [
    ['v' => '(', 't' => 'o'], ['v' => 'a', 't' => 'w'], ['v' => '??', 't' => 'o'],
    ['v' => 'b', 't' => 'w'], ['v' => '?', 't' => 'o'], ['v' => 'c', 't' => 'w'],
    ['v' => ':', 't' => 'o'], ['v' => 'd', 't' => 'w'], ['v' => '&&', 't' => 'o'],
    ['v' => 'e', 't' => 'w'], ['v' => '||', 't' => 'o'], ['v' => 'f', 't' => 'w'],
];
$s = new S($tokens);
$r = p($s);
var_export($r);
echo PHP_EOL;
?>
--EXPECT--
