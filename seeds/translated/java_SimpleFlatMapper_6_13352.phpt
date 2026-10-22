--TEST--
Translated seed (inferredbugs-java: SimpleFlatMapper/6)
--FILE--
<?php

class DbObject {
    public $id;
}

class ResultSetFieldMapperFactory {
    public function getTargetTypeFromSqlType($sqlType) {
        $typeMapping = [
            'LONGNVARCHAR' => 'string',
            'LONGVARCHAR' => 'string',
            'VARCHAR' => 'string',
            'NVARCHAR' => 'string',
            'CHAR' => 'string',
            'NCHAR' => 'string',
            'CLOB' => 'string',
            'NCLOB' => 'string',
            'BIGINT' => 'integer',
            'INTEGER' => 'integer',
            'SMALLINT' => 'integer',
            'TINYINT' => 'integer',
            'FLOAT' => 'double',
            'DOUBLE' => 'double',
            'BOOLEAN' => 'boolean',
            'DATE' => 'string',
            'TIMESTAMP' => 'string',
            'NUMERIC' => 'string'
        ];
        return $typeMapping[$sqlType] ?? null;
    }
}

$factory = new ResultSetFieldMapperFactory();

// Test mapping from SQL types to PHP types
$typesToTest = [
    'LONGNVARCHAR', 'LONGVARCHAR', 'VARCHAR', 'NVARCHAR', 'CHAR', 'NCHAR', 'CLOB', 'NCLOB',
    'BIGINT', 'INTEGER', 'SMALLINT', 'TINYINT', 'FLOAT', 'DOUBLE', 'BOOLEAN', 'DATE', 'TIMESTAMP', 'NUMERIC'
];

foreach ($typesToTest as $type) {
    $targetType = $factory->getTargetTypeFromSqlType($type);
    echo "SQL Type: $type maps to PHP Type: $targetType\n";
}
--EXPECT--
