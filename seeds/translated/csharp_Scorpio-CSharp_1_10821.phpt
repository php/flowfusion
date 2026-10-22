--TEST--
Translated seed (inferredbugs-csharp: Scorpio-CSharp/1)
--FILE--
<?php
$tokens = [];
for ($i = 0; $i < 2000; $i++) {
    $tokens[] = (string)($i % 10);
}
$tokens[] = "x";
$tokens[] = "y";
$tokens[] = "z";
$tokens[] = "a";
$tokens[] = "b";
$tokens[] = "c";
$tokens[] = "d";
$tokens[] = "e";
$tokens[] = "f";
$tokens[] = "g";
$tokens[] = "h";
$tokens[] = "i";
$tokens[] = "j";
$tokens[] = "k";
$tokens[] = "l";
$tokens[] = "m";
$tokens[] = "n";
$tokens[] = "o";
$tokens[] = "p";
$tokens[] = "q";
$tokens[] = "r";
$tokens[] = "s";
$tokens[] = "t";
$tokens[] = "u";
$tokens[] = "v";
$tokens[] = "w";
$tokens[] = "x";
$tokens[] = "y";
$tokens[] = "z";

$stack = [];
$ops = [];
$prec = ['+' => 1, '-' => 1, '*' => 2, '/' => 2, '%' => 2, '^' => 3];
foreach ($tokens as $t) {
    if (ctype_digit($t)) {
        $stack[] = (int)$t;
        while ($ops && end($ops) === '!') {
            array_pop($ops);
            $a = array_pop($stack);
            $stack[] = $a + 1;
        }
    } elseif (isset($prec[$t])) {
        while ($ops && isset($prec[end($ops)]) && $prec[end($ops)] >= $prec[$t]) {
            $op = array_pop($ops);
            $b = array_pop($stack);
            $a = array_pop($stack);
            $stack[] = $op === '+' ? $a + $b : ($op === '-' ? $a - $b : ($op === '*' ? $a * $b : ($op === '/' ? intdiv($a, $b ?: 1) : ($op === '%' ? $a % ($b ?: 1) : $a ** $b))));
        }
        $ops[] = $t;
    } elseif ($t === '!') {
        $ops[] = $t;
    } else {
        $stack[] = strlen($t);
    }
}
while ($ops) {
    $op = array_pop($ops);
    if ($op === '!') {
        $a = array_pop($stack);
        $stack[] = $a + 1;
    } else {
        $b = array_pop($stack);
        $a = array_pop($stack);
        $stack[] = $op === '+' ? $a + $b : ($op === '-' ? $a - $b : ($op === '*' ? $a * $b : ($op === '/' ? intdiv($a, $b ?: 1) : ($op === '%' ? $a % ($b ?: 1) : $a ** $b))));
    }
}
echo array_sum($stack), PHP_EOL;
--EXPECT--
