--TEST--
Translated seed (inferredbugs-csharp: cscs/106)
--FILE--
<?php
$stack = [];
$namespaces = [];
$functions = [];
$variables = [];
$localScopes = [];
$currentNamespace = '';
$namespacePrefix = '';

$stack[] = ['name' => 'ns', 'isNamespace' => true, 'vars' => []];
$namespaces['ns'] = &$stack[0];
$currentNamespace = 'ns';
$namespacePrefix = 'ns.';

$variables['x'] = ['name' => 'x', 'value' => 1];
$variables['ns.x'] = ['name' => 'ns.x', 'value' => 2];
$localScopes['scope']['y'] = ['name' => 'y', 'value' => 3];

$stack[] = ['name' => 'inner', 'isNamespace' => false, 'vars' => ['z' => ['name' => 'z', 'value' => 4]]];
$last = &$stack[count($stack) - 1];

$key = $namespacePrefix . 'x';
if (isset($namespaces['ns']['vars'][$key])) {
    $v = $namespaces['ns']['vars'][$key];
} elseif (isset($variables[$key])) {
    $v = $variables[$key];
} else {
    $v = $variables['ns.x'];
}

$localScopes['scope']['x'] = $v;
$functions['f'] = $v;

$copy = [];
foreach ($variables as $k => $item) {
    $copy[$k] = $item;
}
foreach ($last['vars'] as $k => $item) {
    $copy[$k] = $item;
}
foreach ($localScopes['scope'] as $k => $item) {
    $copy[$k] = $item;
}

unset($stack[count($stack) - 1]);
$currentNamespace = '';
$namespacePrefix = '';

echo count($copy) . "\n";
echo $v['name'] . ":" . $v['value'] . "\n";
echo isset($functions['f']) ? "ok\n" : "fail\n";
?>
--EXPECT--
