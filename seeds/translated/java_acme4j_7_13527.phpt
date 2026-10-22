--TEST--
Translated seed (inferredbugs-java: acme4j/7)
--FILE--
<?php

$jsonString = '{"foo":"a-text","bar":123}';
$jsonDecoded = json_decode($jsonString, true);

if ($jsonDecoded === null) {
    exit;
}

$jsonBad = json_decode("This is no JSON.", true);

if ($jsonBad === null) {
    exit;
}

$jsonGet = json_decode('{"text":"lorem ipsum","number":123,"boolean":true,"none":null,"array":[{"foo":"bar"},{"foo":"baz"}]}', true);
if (isset($jsonGet['none'])) {
    $none = $jsonGet['none'];
    var_dump($none); // should be null
}

$textValue = $jsonGet['text'];
if (is_string($textValue)) {
    $textValueLength = strlen($textValue);
}

$intValue = $jsonGet['number'];
if (is_int($intValue)) {
    $intValueSquared = $intValue * $intValue;
}

$boolValue = $jsonGet['boolean'];
if (is_bool($boolValue)) {
    $boolValueNegated = !$boolValue;
}

$arrayValue = $jsonGet['array'];
foreach ($arrayValue as $item) {
    if (isset($item['foo'])) {
        $fooValue = $item['foo'];
    }
}

$wrongGetter = $jsonGet['text'];
if (is_array($wrongGetter)) {
    exit; // should not happen
}

$serialized = serialize($jsonGet);
$unserialized = unserialize($serialized);

if ($unserialized !== $jsonGet) {
    exit;
}
--EXPECT--
