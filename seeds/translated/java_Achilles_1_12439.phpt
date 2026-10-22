--TEST--
Translated seed (inferredbugs-java: Achilles/1)
--FILE--
<?php

function prepareInsertPS($tableName, $idMeta, $fieldMetas) {
    $insert = "INSERT INTO $tableName (";
    $values = "VALUES (";
    $fields = [];
    
    if ($idMeta['isEmbeddedId']) {
        foreach ($idMeta['componentNames'] as $component) {
            $fields[] = $component;
        }
    } else {
        $fields[] = $idMeta['propertyName'];
    }
    
    foreach ($fieldMetas as $pm) {
        if ($pm['propertyName'] !== $idMeta['propertyName']) {
            $fields[] = $pm['propertyName'];
        }
    }
    
    $insert .= implode(", ", $fields) . ") ";
    $values .= implode(", ", array_fill(0, count($fields), '?')) . ")";
    
    return $insert . $values;
}

function main() {
    $tableName = 'example_table';
    $idMeta = [
        'isEmbeddedId' => false,
        'propertyName' => 'id',
        'componentNames' => []
    ];
    $fieldMetas = [
        ['propertyName' => 'name'],
        ['propertyName' => 'age']
    ];

    $query = prepareInsertPS($tableName, $idMeta, $fieldMetas);
    echo $query;
}

main();

?>
--EXPECT--
