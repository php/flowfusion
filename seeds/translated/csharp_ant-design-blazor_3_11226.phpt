--TEST--
Translated seed (inferredbugs-csharp: ant-design-blazor/3)
--FILE--
<?php
$items = [
    ['name' => 'a', 'value' => 1],
    ['name' => 'b', 'value' => null],
    ['name' => 'c', 'value' => 2],
    ['name' => 'd', 'value' => 1],
];

$filters = [
    ['value' => 1, 'op' => 'eq', 'cond' => 'or'],
    ['value' => null, 'op' => 'isnull', 'cond' => 'and'],
    ['value' => 2, 'op' => 'eq', 'cond' => 'or'],
];

$mode = 'list';
$selectedValues = array_map(fn($x) => $x['value'] === null ? null : (string)$x['value'], $filters);

$result = [];
if (count($filters) > 0) {
    if ($mode === 'list') {
        foreach ($items as $item) {
            $match = false;
            foreach ($filters as $f) {
                if ($f['value'] === null && $f['op'] !== 'isnull' && $f['op'] !== 'isnotnull') {
                    continue;
                }
                $match = $match || ($item['value'] == $f['value']);
            }
            if ($match) {
                $result[] = $item;
            }
        }
    } else {
        foreach ($items as $item) {
            $expr = null;
            foreach ($filters as $f) {
                if ($f['value'] === null && $f['op'] !== 'isnull' && $f['op'] !== 'isnotnull') {
                    continue;
                }
                $v = $f['op'] === 'isnull' || $f['op'] === 'isnotnull' ? null : $f['value'];
                $cur = $f['op'] === 'isnull' ? ($item['value'] === null) : ($f['op'] === 'isnotnull' ? ($item['value'] !== null) : ($item['value'] == $v));
                $expr = $expr === null ? $cur : (($f['cond'] ?? 'or') === 'and' ? ($expr && $cur) : ($expr || $cur));
            }
            if ($expr) {
                $result[] = $item;
            }
        }
    }
}

echo count($result), "\n";
?>
--EXPECT--
