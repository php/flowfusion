--TEST--
Translated seed (inferredbugs-csharp: crayon/2)
--FILE--
<?php
$tokens = ['0', '.', '1', ';'];
$i = 0;
while ($i < count($tokens)) {
    $next = $tokens[$i];
    if ($next === '.' && isset($tokens[$i + 1])) {
        $num = $tokens[$i + 1];
        if ($num !== '' && ctype_digit($num)) {
            $v = (float)('0.' . $num);
        }
        $i += 2;
        continue;
    }
    if ($next !== '' && ctype_digit($next)) {
        if (isset($tokens[$i + 1]) && $tokens[$i + 1] === '.') {
            $dec = $tokens[$i + 2] ?? '';
            if ($dec !== '' && ctype_digit($dec)) {
                $v = (float)($next . '.' . $dec);
                $i += 3;
                continue;
            }
        }
        $n = (int)$next;
    }
    $i++;
}
echo "ok\n";
?>
--EXPECT--
