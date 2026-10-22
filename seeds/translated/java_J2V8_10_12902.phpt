--TEST--
Translated seed (inferredbugs-java: J2V8/10)
--FILE--
<?php

function convertToMap($object) {
    if ($object === null) {
        return [];
    }
    $result = [];
    foreach ($object as $key => $value) {
        $result[$key] = $value;
    }
    return $result;
}

function convertToList($array) {
    if ($array === null) {
        return [];
    }
    $result = [];
    foreach ($array as $value) {
        $result[] = $value;
    }
    return $result;
}

function exampleBugPattern() {
    // Simulate a deep copy scenario with nested arrays and objects
    $nestedArray = [
        "name" => "Example",
        "data" => [
            "values" => [1, 2, 3],
            "meta" => [
                "type" => "numeric",
                "count" => 3
            ]
        ]
    ];

    $map = convertToMap($nestedArray);
    $list = convertToList($map["data"]["values"]);

    // Alter the original array to see if it affects the map/list
    $nestedArray["data"]["values"][] = 4;

    // Will print out the original list without the new value
    print_r($list);
}

exampleBugPattern();

?>
--EXPECT--
