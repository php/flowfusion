--TEST--
Translated seed (inferredbugs-java: J2V8/7)
--FILE--
<?php

$object = new stdClass();
$object->name = "Test";
$object->value = 42;
$object->list = [1, 2, 3, 4, 5];
$object->nested = new stdClass();
$object->nested->key = "value";

function objectToArray($obj) {
    if (is_object($obj)) {
        $obj = (array)$obj;
    }
    if (is_array($obj)) {
        return array_map('objectToArray', $obj);
    } else {
        return $obj;
    }
}

$array = objectToArray($object);
$array['list'][] = 6;

function arrayToObject($array) {
    if (!is_array($array)) {
        return $array;
    }
    $obj = new stdClass();
    foreach ($array as $key => $value) {
        $obj->$key = arrayToObject($value);
    }
    return $obj;
}

$newObject = arrayToObject($array);

var_dump($newObject);
--EXPECT--
