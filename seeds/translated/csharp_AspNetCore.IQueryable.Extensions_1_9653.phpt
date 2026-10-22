--TEST--
Translated seed (inferredbugs-csharp: AspNetCore.IQueryable.Extensions/1)
--FILE--
<?php

class QueryPaging {
    public $Limit = 10;
    public $Offset = 0;
}

class QueryOperatorAttribute {
    public $Max;

    public function __construct($max) {
        $this->Max = $max;
    }
}

function getProperty($object, $property) {
    return $object->$property ?? null;
}

function paginate($result, $limit = 10, $offset = 0) {
    if ($limit <= 0) {
        $limit = 10;
    }
    return array_slice($result, $offset, $limit);
}

function paginateWithAttributes($result, $options) {
    $attr = new QueryOperatorAttribute(5); // Setting Max to 5 for the example
    $property = getProperty($options, 'Limit');
    
    if ($attr instanceof QueryOperatorAttribute && $attr->Max > 0) {
        $options->Limit = $attr->Max;
    }

    return array_slice($result, $options->Offset, $options->Limit);
}

function main() {
    $data = range(1, 100);
    $options = new QueryPaging();
    $options->Offset = 0;

    $result = paginate($data);
    print_r($result);

    $options->Offset = 10;
    $resultWithAttributes = paginateWithAttributes($data, $options);
    print_r($resultWithAttributes);
}

main();
--EXPECT--
