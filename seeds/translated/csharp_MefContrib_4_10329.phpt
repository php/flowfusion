--TEST--
Translated seed (inferredbugs-csharp: MefContrib/4)
--FILE--
<?php
$map = [
    'System.Collections.Generic.List`1' => 'System.Collections.Generic.ListImpl`1',
    'System.Collections.Generic.Dictionary`2' => 'System.Collections.Generic.DictionaryImpl`2',
];

$manufactured = [];
$catalog = [];

$imports = [
    ['type' => 'System.Collections.Generic.List`1', 'exports' => []],
    ['type' => 'System.Collections.Generic.List`1', 'exports' => []],
    ['type' => 'System.String', 'exports' => []],
    ['type' => 'System.Collections.Generic.Dictionary`2', 'exports' => [['part' => 'prebuilt', 'export' => 'x']]],
];

foreach ($imports as $definition) {
    $exports = $definition['exports'];
    if ($exports) {
        continue;
    }

    $type = $definition['type'];
    if (strpos($type, '`') === false) {
        continue;
    }

    if (isset($manufactured[$type])) {
        continue;
    }

    if (!isset($map[$type])) {
        continue;
    }

    $manufactured[$type] = true;
    $catalog[] = $map[$type];
}

echo count($catalog), "\n";
echo implode(',', $catalog), "\n";
?>
--EXPECT--
