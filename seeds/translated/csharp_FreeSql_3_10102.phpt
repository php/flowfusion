--TEST--
Translated seed (inferredbugs-csharp: FreeSql/3)
--FILE--
<?php
declare(strict_types=1);

$tables = [
    ['alias' => 't0', 'type' => 'A'],
    ['alias' => 't1', 'type' => 'A'],
];

$groupByLimit = 2;
$groupBySkip = 1;
$ormDataType = 'Oracle';

$field = 'x';
if ($field === '') {
    throw new InvalidArgumentException('Parameter_Field_NotSpecified');
}

$isNestedPageSql = in_array($ormDataType, ['Oracle', 'OdbcOracle', 'Dameng', 'OdbcDameng', 'GBase'], true);

$sql = 'select ' . $field . ' from dual';

if (!$isNestedPageSql) {
    $groupByLimit = 0;
    $groupBySkip = 0;
    echo $sql;
    exit;
}

if ($groupByLimit === 0 && $groupBySkip === 0) {
    echo $sql;
    exit;
}

$wrapped = 'select * from (' . $sql . ') t limit ' . $groupByLimit . ' offset ' . $groupBySkip;
echo $wrapped;
?>
--EXPECT--
