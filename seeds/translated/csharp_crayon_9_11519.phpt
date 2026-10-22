--TEST--
Translated seed (inferredbugs-csharp: crayon/9)
--FILE--
<?php
class Token {
    public string $v;
    function __construct(string $v) { $this->v = $v; }
}
class T {
    public array $a;
    public int $i = 0;
    function __construct(array $a) { $this->a = $a; }
    function p() { return $this->a[$this->i++] ?? new Token(''); }
    function n() { return $this->a[$this->i]->v ?? ''; }
    function popIf(string $v): bool { if ($this->n() === $v) { $this->i++; return true; } return false; }
}
function parseExpr(T $t): mixed {
    $e = parseAdd($t);
    if ($t->n() === '?') { $t->p(); parseExpr($t); $t->popIf(':'); parseExpr($t); }
    return $e;
}
function parseAdd(T $t): mixed {
    $e = parseMul($t);
    while (in_array($t->n(), ['+','-'], true)) { $t->p(); $e = [parseMul($t), $e]; }
    return $e;
}
function parseMul(T $t): mixed {
    $e = parseUnary($t);
    while (in_array($t->n(), ['*','/','%'], true)) { $t->p(); $e = [parseUnary($t), $e]; }
    return $e;
}
function parseUnary(T $t): mixed {
    if (in_array($t->n(), ['!','-'], true)) { $op = $t->p()->v; return [$op, parseUnary($t)]; }
    return parsePrimary($t);
}
function parsePrimary(T $t): mixed {
    $x = $t->p()->v;
    if ($x === '(') {
        if ($t->popIf(')')) return ['lambda', []];
        $e = parseExpr($t);
        if ($t->popIf(')') && $t->n() === '=>') { $t->p(); return ['lambda', $e]; }
        return $e;
    }
    if (ctype_digit($x[0] ?? '')) return strpos($x, '.') !== false ? (float)$x : (int)$x;
    return $x;
}
$t = new T([
    new Token('('), new Token('a'), new Token(','), new Token('b'), new Token(')'), new Token('=>'),
    new Token('1'), new Token('+'), new Token('2'), new Token('*'), new Token('3'),
    new Token('?'), new Token('4'), new Token(':'), new Token('5')
]);
parseExpr($t);
echo "ok\n";
?>
--EXPECT--
