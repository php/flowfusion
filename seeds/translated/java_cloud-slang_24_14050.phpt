--TEST--
Translated seed (inferredbugs-java: cloud-slang/24)
--FILE--
<?php

$stepsData = [];
$personMap = [
    "john" => 1,
    "jane" => 2,
    "peter" => "three"
];

foreach ($personMap as $name => $value) {
    $stepsData[] = [
        'inputs' => [
            'text' => $name,
            'text2' => $value
        ]
    ];
}

$actualContextSet = array_map(function($step) {
    return $step['inputs'];
}, $stepsData);

$expectedContextSet = [
    ['text' => 'john', 'text2' => 1],
    ['text' => 'jane', 'text2' => 2],
    ['text' => 'peter', 'text2' => 'three']
];

sort($actualContextSet);
sort($expectedContextSet);

if ($actualContextSet !== $expectedContextSet) {
    exit("Loop task inputs not as expected");
}
--EXPECT--
