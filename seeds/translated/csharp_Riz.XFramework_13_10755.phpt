--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/13)
--FILE--
<?php
$collections = [[1, 2, 3], new ArrayIterator([1, 2, 3]), range(1, 3)];
foreach ($collections as $c) {
    $i = -1;
    foreach ($c as $v) {
        $i++;
        if ($v === 2) break;
    }
    $pageSize = 2;
    $count = is_array($c) ? count($c) : iterator_count(clone $c);
    $page = intdiv($count + $pageSize - 1, $pageSize);
    $mapped = array_values(array_map(fn($x) => $x * 2, is_array($c) ? $c : iterator_to_array(clone $c, false)));
    $filtered = array_values(array_map(fn($x) => $x + 1, array_filter($mapped, fn($x) => $x > 2)));
    $nested = [[['k' => 'v']]];
    $key = $nested[0][0]['k'];
    $s = implode('.', ['a', 'b', 'c']);
    $x = $i + $page + count($filtered) + strlen($key) + strlen($s);
}
echo "ok\n";
?>
--EXPECT--
