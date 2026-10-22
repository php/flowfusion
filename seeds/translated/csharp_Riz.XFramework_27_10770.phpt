--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/27)
--FILE--
<?php
$visited = [];
$nav = [];
$expr = ['type' => 'binary', 'op' => 'and', 'left' => ['type' => 'binary', 'op' => 'or', 'left' => ['type' => 'const', 'value' => true], 'right' => ['type' => 'member', 'name' => 'flag']], 'right' => ['type' => 'binary', 'op' => 'coalesce', 'left' => ['type' => 'member', 'name' => 'name'], 'right' => ['type' => 'const', 'value' => 'x']]];
$stack = [];
$visit = function ($node) use (&$visit, &$visited, &$nav, &$stack) {
    if ($node === null) return null;
    switch ($node['type']) {
        case 'const':
            return $node;
        case 'member':
            $stack[] = $node['name'];
            $visited[] = $node['name'];
            return $node;
        case 'binary':
            if ($node['op'] === 'coalesce') return $visit($node['left']);
            $l = $visit($node['left']);
            $r = $visit($node['right']);
            if ($node['op'] === 'and' || $node['op'] === 'or') {
                if ($l['type'] === 'const' && is_bool($l['value'])) $l = ['type' => 'binary', 'op' => 'eq', 'left' => ['type' => 'const', 'value' => 1], 'right' => ['type' => 'const', 'value' => $l['value'] ? 1 : 2]];
                if ($r['type'] === 'const' && is_bool($r['value'])) $r = ['type' => 'binary', 'op' => 'eq', 'left' => ['type' => 'const', 'value' => 1], 'right' => ['type' => 'const', 'value' => $r['value'] ? 1 : 2]];
            }
            return ['type' => 'binary', 'op' => $node['op'], 'left' => $l, 'right' => $r];
    }
    return $node;
};
$node = $visit($expr);
if (count($stack) > 0) {
    while (count($stack) > 0) {
        $nav[] = array_pop($stack);
    }
}
echo json_encode($node, JSON_UNESCAPED_SLASHES), PHP_EOL;
echo implode(',', $visited), PHP_EOL;
echo implode(',', $nav), PHP_EOL;
?>
--EXPECT--
