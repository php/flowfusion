--TEST--
Translated seed (inferredbugs-csharp: UABEA/1)
--FILE--
<?php
$root = ['header' => 'root', 'items' => [], 'tag' => ['loaded' => false]];
$workspace = [
    'base' => [
        'type' => 'PPtr<Thing>',
        'name' => 'm_Ref',
        'childrenCount' => 2,
        'template' => ['type' => 'PPtr<Thing>', 'isArray' => false, 'children' => []],
        'value' => null,
        'children' => [
            ['type' => 'int', 'name' => 'm_FileID', 'childrenCount' => 0, 'dummy' => false, 'value' => ['type' => 'int', 'value' => 0], 'children' => []],
            ['type' => 'long', 'name' => 'm_PathID', 'childrenCount' => 0, 'dummy' => false, 'value' => ['type' => 'int64', 'value' => 123], 'children' => []]
        ]
    ]
];

$baseField = $workspace['base'];
$baseItem = ['header' => $baseField['type'] . ' ' . $baseField['name'], 'items' => [['header' => 'Loading...', 'items' => []]], 'tag' => ['loaded' => false]];
$root['items'][] = $baseItem;

if (!$root['items'][0]['tag']['loaded']) {
    $root['items'][0]['tag']['loaded'] = true;
    $assetField = $baseField;
    if ($assetField['childrenCount'] > 0) {
        $items = [];
        foreach ($assetField['children'] as $childField) {
            $value = '';
            if ($childField['value'] !== null) {
                $evt = $childField['value']['type'];
                $quote = $evt === 'string' ? '"' : '';
                if ($evt === 'string' || $evt === 'int' || $evt === 'int64') {
                    $value = ' = ' . $quote . $childField['value']['value'] . $quote;
                }
            }
            $items[] = ['header' => $childField['type'] . ' ' . $childField['name'] . $value, 'items' => []];
        }
        $root['items'][0]['items'] = $items;
    }
}

echo "ok\n";
?>
--EXPECT--
