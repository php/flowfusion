--TEST--
Translated seed (inferredbugs-java: J2V8/8)
--FILE--
<?php

$array = [1, 2, 3, 4, 5];
$map = ["a" => 1, "b" => ["c" => 2, "d" => 3], "e" => true];

function toArray($array) {
    return array_map(function($value) {
        if (is_array($value)) {
            return toArray($value);
        }
        return $value;
    }, $array);
}

function toMap($map) {
    $result = [];
    foreach ($map as $key => $value) {
        if (is_array($value)) {
            $result[$key] = toArray($value);
        } else {
            $result[$key] = $value;
        }
    }
    return $result;
}

$deepCopyArray = toArray($array);
$deepCopyMap = toMap($map);
--EXPECT--
