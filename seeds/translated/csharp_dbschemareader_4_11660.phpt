--TEST--
Translated seed (inferredbugs-csharp: dbschemareader/4)
--FILE--
<?php
$cols = [
    ['name' => 'id', 'pk' => true, 'fk' => true, 'ref' => 'User', 'type' => 'int'],
    ['name' => 'user', 'pk' => false, 'fk' => false, 'type' => 'string'],
    ['name' => 'group_id', 'pk' => false, 'fk' => true, 'ref' => 'Group', 'type' => 'int'],
    ['name' => 'id', 'pk' => false, 'fk' => false, 'type' => 'string'],
];
$children = [
    ['name' => 'members', 'many' => true, 'target' => 'User'],
    ['name' => 'owner', 'many' => false, 'target' => 'User'],
];
$fk = ['columns' => ['id', 'group_id'], 'ref' => 'Group'];
$out = [];
foreach ($cols as $c) {
    $p = $c['name'];
    if ($p === 'User') $p .= 'Column';
    if ($c['pk'] && $c['fk'] && $c['ref'] === $p) $p .= 'Id';
    $out[] = $p . ':' . $c['type'];
}
foreach ($children as $ch) {
    $t = $ch['many'] ? 'array' : 'object';
    $out[] = $ch['name'] . ':' . $t . ':' . $ch['target'];
}
foreach ($fk['columns'] as $n) {
    foreach ($cols as $c) {
        if ($c['name'] === $n && !$c['pk']) $out[] = $c['name'] . 'Id';
    }
}
echo implode("\n", $out), "\n";
?>
--EXPECT--
