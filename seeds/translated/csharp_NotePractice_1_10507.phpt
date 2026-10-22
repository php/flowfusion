--TEST--
Translated seed (inferredbugs-csharp: NotePractice/1)
--FILE--
<?php
$tableName = '';
$dt = new ArrayObject([], ArrayObject::ARRAY_AS_PROPS);
$columns = [];
for ($i = 0; $i < count($columns); $i++) {
    $mappings[$columns[$i]] = $columns[$i];
}
$timeout = 600;
$destinationTableName = $tableName;
$data = iterator_to_array($dt);
?>
--EXPECT--
