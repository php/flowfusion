--TEST--
Translated seed (inferredbugs-csharp: Musoq/1)
--FILE--
<?php
$nodes = [];
$generatedColumns = [];
$schemaFromArgs = [null, 'x', 1, true];
$generatedAliases = [];
$setKey = 0;

$fields = [
    ['name' => 'alpha', 'expr' => 'all'],
    ['name' => 'beta', 'expr' => 'x'],
    ['name' => 'gamma', 'expr' => 'all'],
];

$generatedColumns = [];
for ($i = 0; $i < 3; $i++) {
    $generatedColumns[] = ['expr' => "col$i", 'fieldName' => $i === 1 ? 'beta' : ($i === 0 ? 'alpha' : 'gamma')];
}

$reordered = [];
for ($i = count($fields) - 1; $i >= 0; $i--) {
    $reordered[$i] = $fields[$i];
}

$out = [];
for ($i = 0, $p = 0, $j = count($reordered); $i < $j; $i++) {
    $field = $reordered[$i];
    if ($field['expr'] === 'all') {
        foreach ($generatedColumns as $column) {
            $out[] = ['expr' => $column['expr'], 'pos' => $p++, 'name' => $column['fieldName']];
        }
        continue;
    }
    $out[] = ['expr' => $field['expr'], 'pos' => $p++, 'name' => $field['name']];
}

$keys = ['beta', 'gamma'];
$indexes = [];
$fieldIndex = 0;
$index = 0;
foreach ($out as $field) {
    if (in_array($field['name'], $keys, true)) {
        $indexes[$index++] = $fieldIndex;
    }
    $fieldIndex++;
}

$key = (string)$setKey++;
$alias = '';
if ($alias === '') {
    $alias = 'q' . count($generatedAliases);
}
$generatedAliases[] = $alias;

$schemaFromArgs = [];
$schemaFromArgs[] = $key;
$schemaFromArgs[] = $alias;
$schemaFromArgs[] = $indexes;

echo '';
?>
--EXPECT--
