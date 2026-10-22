--TEST--
Translated seed (inferredbugs-csharp: Transformalize/6)
--FILE--
<?php
$entity = (object)[
    'alias' => str_repeat('x', 40),
    'schema' => 's',
    'outputName' => 'o',
    'fields' => [],
    'calculatedFields' => [],
    'group' => false,
    'isFirstRun' => false,
    'useBcp' => false,
    'inputKeys' => [1, 2, 3],
    'inputConnection' => (object)[
        'provider' => (object)['type' => 'file', 'isDatabase' => false],
        'isDelimited' => function () { return true; }
    ],
    'outputConnection' => (object)[
        'provider' => (object)['isDatabase' => false],
        'recordsExist' => function ($schema, $name) { return false; },
        'dropUniqueClusteredIndex' => function ($e) {},
        'dropPrimaryKey' => function ($e) {},
        'addUniqueClusteredIndex' => function ($e) {},
        'addPrimaryKey' => function ($e) {}
    ]
];

$entity->isFirstRun = !($entity->outputConnection->recordsExist)($entity->schema, $entity->outputName);
$entity->inputKeys = [];
if ($entity->isFirstRun && ($entity->outputConnection->provider->isDatabase)) {
    ($entity->outputConnection->dropUniqueClusteredIndex)($entity);
    ($entity->outputConnection->dropPrimaryKey)($entity);
}
if ($entity->isFirstRun && ($entity->outputConnection->provider->isDatabase)) {
    ($entity->outputConnection->addUniqueClusteredIndex)($entity);
    ($entity->outputConnection->addPrimaryKey)($entity);
}
echo "ok\n";
--EXPECT--
