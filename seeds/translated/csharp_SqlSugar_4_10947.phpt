--TEST--
Translated seed (inferredbugs-csharp: SqlSugar/4)
--FILE--
<?php
for ($i = 0; $i < 1; $i++) {
    $db = new stdClass();
    $db->ConnectionString = 'x';
    $db->DbType = 'SqlServer';
    $query = [
        'Student',
        'School',
        [
            'JoinType' => 'Left',
            'Condition' => ['SchoolId', '==', 'Id']
        ]
    ];
    $unused = $query;
}
?>
--EXPECT--
