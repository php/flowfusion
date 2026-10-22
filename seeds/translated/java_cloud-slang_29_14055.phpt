--TEST--
Translated seed (inferredbugs-java: cloud-slang/29)
--FILE--
<?php

$map = [
    "john" => 1,
    "jane" => 2,
    "peter" => "three"
];

$actualTasks = [];
foreach ($map as $name => $value) {
    $actualTasks[] = [
        "text" => $name,
        "text2" => $value
    ];
}

$expectedTasks = [
    ["text" => "john", "text2" => 1],
    ["text" => "jane", "text2" => 2],
    ["text" => "peter", "text2" => "three"]
];

if (count($actualTasks) !== count($expectedTasks)) {
    exit(1);
}

foreach ($expectedTasks as $key => $expected) {
    if ($actualTasks[$key] !== $expected) {
        exit(1);
    }
}

$breakTest = 0;
foreach ($map as $name => $value) {
    if ($value === 2) {
        break;
    }
    $breakTest++;
}

if ($breakTest !== 1) {
    exit(1);
}
--EXPECT--
