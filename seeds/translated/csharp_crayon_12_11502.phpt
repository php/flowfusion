--TEST--
Translated seed (inferredbugs-csharp: crayon/12)
--FILE--
<?php
$tokens = ['0', '.', '1', ';', 'a', '+=', '2', ';', 'true', '&&', 'false', '||', 'true', ';'];
$i = 0;
$peek = function() use (&$tokens, &$i) { return $tokens[$i] ?? null; };
$pop = function() use (&$tokens, &$i) { return $tokens[$i++] ?? null; };
$parseNumber = function() use (&$peek, &$pop) {
    $n = $pop();
    if ($peek() === '.') {
        $pop();
        $d = $pop();
        $n = $n . '.' . $d;
    }
    return $n + 0;
};
$a = $parseNumber();
$var = $pop();
$op = $pop();
$rhs = $parseNumber();
$varValue = 1;
if ($op === '+=') $varValue += $rhs;
$lhs = $pop() === 'true';
$and = $pop();
$mid = $pop() === 'true';
$or = $pop();
$rhsb = $pop() === 'true';
$result = ($lhs && $mid) || $rhsb;
echo $a, "\n", $varValue, "\n", ($result ? 'true' : 'false'), "\n";
?>
--EXPECT--
