--TEST--
Translated seed (inferredbugs-csharp: AspNetCore.IQueryable.Extensions/2)
--FILE--
<?php

class QueryPaging {
    public $Limit;
    public $Offset;
}

function paginate($result, $limit = 10, $offset = 0) {
    if ($limit <= 0) {
        $limit = 10;
    }
    
    return array_slice($result, $offset, $limit);
}

function paginateWithOptions($result, $options) {
    if (isset($options->Limit) && $options->Limit > 0) {
        $limit = $options->Limit;
    } else {
        $limit = null;
    }
    
    if (isset($options->Offset)) {
        $result = array_slice($result, $options->Offset);
    }

    if (isset($limit)) {
        $result = array_slice($result, 0, $limit);
    }

    return $result;
}

function main() {
    $data = range(1, 100);
    
    $options = new QueryPaging();
    $options->Limit = 10;
    $options->Offset = 5;

    $pagedData = paginate($data, 10, 5);
    $pagedDataWithOptions = paginateWithOptions($data, $options);
}

main();
--EXPECT--
