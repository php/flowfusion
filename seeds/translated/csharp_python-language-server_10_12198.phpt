--TEST--
Translated seed (inferredbugs-csharp: python-language-server/10)
--FILE--
<?php
$items = ['name' => 'f', 'doc' => 'd', 'params' => ['x', 'y'], 'return' => ['typeid' => 1, 'name' => 'int', 'doc' => 'rd']];
$call = function(array $args = []) use ($items) {
    return $items['return'];
};

$r = $call();
if ($r === null) {
    exit(1);
}
if ($r['typeid'] !== 1) {
    exit(1);
}
if ($items['doc'] !== 'd') {
    exit(1);
}
if (($call())['doc'] ?? 'rd' !== 'rd') {
    exit(1);
}
if ($items['name'] !== 'f') {
    exit(1);
}
if (count($items['params']) <= 1) {
    $p = $items['params'][0] ?? null;
} else {
    $p = $items['params'][1];
}
if (count($items['params']) !== 2) {
    exit(1);
}
$curr = array_map(fn($x) => $x, $items['params']);
$exp = ['x', 'y'];
sort($curr);
sort($exp);
if ($curr !== $exp) {
    exit(1);
}
if ($r['name'] !== 'int') {
    exit(1);
}
?>
--EXPECT--
