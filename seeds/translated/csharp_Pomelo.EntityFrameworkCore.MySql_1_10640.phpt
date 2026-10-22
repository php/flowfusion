--TEST--
Translated seed (inferredbugs-csharp: Pomelo.EntityFrameworkCore.MySql/1)
--FILE--
<?php

$views = null;
$schema = 'test_schema';
$tables = [
    ['TABLE_SCHEMA' => $schema, 'TABLE_NAME' => 'v1'],
    ['TABLE_SCHEMA' => $schema, 'TABLE_NAME' => 'v2'],
    ['TABLE_SCHEMA' => 'other_schema', 'TABLE_NAME' => 'v3'],
];

$matches = [];
foreach ($tables as $row) {
    if ($row['TABLE_SCHEMA'] === $schema) {
        $matches[] = '`' . $row['TABLE_SCHEMA'] . '.' . $row['TABLE_NAME'] . '`';
    }
}

if ($matches) {
    $views = 'DROP VIEW IF EXISTS ' . implode(',', $matches);
} else {
    $views = 'SELECT 0';
}

if ($views === null) {
    $views = 'SELECT 0';
}

echo $views, PHP_EOL;
--EXPECT--
