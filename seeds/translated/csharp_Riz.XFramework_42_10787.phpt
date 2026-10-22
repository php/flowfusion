--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/42)
--FILE--
<?php
$items = [];
for ($i = 0; $i < 1005; $i++) $items[] = ['p' => $i];

$chunks = [];
$ctx = ['params' => []];
foreach ($items as $k => $v) {
    $ctx['params'][] = $v;
    $chunks[] = 'x=' . $v['p'];
    if (count($ctx['params']) > 1000) {
        $chunks[] = null;
        $ctx = ['params' => []];
    }
}

$bulk = array_chunk($items, 1000);
$out = [];
foreach ($bulk as $page) {
    $s = '';
    $n = count($page);
    foreach ($page as $i => $row) {
        $s .= ($i ? ',' : '') . $row['p'];
    }
    if ($s !== '') $out[] = $s;
}

echo implode("\n", array_map(fn($x) => $x === null ? 'NULL' : $x, array_merge($chunks, $out))), "\n";
?>
--EXPECT--
