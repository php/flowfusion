--TEST--
Translated seed (inferredbugs-csharp: Transformalize/2)
--FILE--
<?php
$entities = [
    ['schema' => 'dbo', 'alias' => 'master', 'output' => 'master_table', 'isMaster' => true, 'all' => ['id', 'name'], 'calculated' => ['calc1']],
    ['schema' => 'dbo', 'alias' => 'child', 'output' => 'child_table', 'isMaster' => false, 'all' => ['id', 'parent_id'], 'calculated' => []],
    ['schema' => 'dbo', 'alias' => 'other', 'output' => 'other_table', 'isMaster' => false, 'all' => ['id', 'x'], 'calculated' => []],
];

$relationships = [
    [
        'left' => 'child',
        'right' => 'other',
        'leftCount' => 1,
        'rightCount' => 2,
        'join' => [
            ['leftField' => 'child_fk', 'rightField' => 'other_fk'],
            ['leftField' => 'child_code', 'rightField' => 'other_code'],
        ],
    ],
    [
        'left' => 'other',
        'right' => 'master',
        'leftCount' => 3,
        'rightCount' => 1,
        'join' => [
            ['leftField' => 'other_master_id', 'rightField' => 'master_other_id'],
        ],
    ],
];

foreach ($entities as $entity) {
    $relationshipFields = [];
    if ($entity['isMaster']) {
        foreach ($relationships as $r) {
            if ($r['left'] !== $entity['alias'] && $r['right'] !== $entity['alias']) {
                if ($r['leftCount'] <= $r['rightCount']) {
                    foreach ($r['join'] as $j) $relationshipFields[] = $j['leftField'];
                } else {
                    foreach ($r['join'] as $j) $relationshipFields[] = $j['rightField'];
                }
            }
        }
    }

    $defs = array_values(array_unique(array_merge($entity['all'], $entity['calculated'], $relationshipFields)));
    $primaryKey = $entity['isMaster'] ? 'master_key' : 'primary_key';

    $cols = [];
    foreach ($defs as $d) {
        $cols[] = "`$d` VARCHAR(255)" . (($d === 'master_key' || $d === 'primary_key') ? ' NOT NULL' : '');
    }

    $sql = 'CREATE TABLE IF NOT EXISTS `' . $entity['output'] . '` (' . implode(', ', $cols) . ', PRIMARY KEY (`' . $primaryKey . '`))';
    echo $sql, PHP_EOL;
}
?>
--EXPECT--
