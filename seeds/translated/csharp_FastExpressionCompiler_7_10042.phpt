--TEST--
Translated seed (inferredbugs-csharp: FastExpressionCompiler/7)
--FILE--
<?php
$nodes = range(1, 8);
$visited = [];
foreach ($nodes as $i => $v) {
    $visited[$i] = ($v & 1) ? $v : $v;
}
$copy = null;
for ($i = 0, $n = count($visited); $i < $n; $i++) {
    $new = $visited[$i];
    if ($copy !== null) {
        $copy[$i] = $new;
    } elseif ($new !== $visited[$i]) {
        $copy = array_fill(0, $n, null);
        for ($j = 0; $j < $i; $j++) {
            $copy[$j] = $visited[$j];
        }
        $copy[$i] = $new;
    }
}
$result = $copy ?? $visited;
foreach ($result as $k => $v) {
    if (($k & 1) === 0) {
        $result[$k] = $v;
    }
}
echo count($result), "\n";
?>
--EXPECT--
