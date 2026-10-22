--TEST--
Translated seed (inferredbugs-csharp: TensorFlowSharp/1)
--FILE--
<?php
echo getcwd(), PHP_EOL;
echo 'PHP version: ' . PHP_VERSION . PHP_EOL;

$graph = [];
$graph['Placeholder'] = ['op' => 'Placeholder', 'name' => 'feed', 'dtype' => 'int32'];
$graph['Const'] = ['op' => 'Const', 'name' => 'scalar', 'value' => 0];

$placeholder = $graph['Placeholder'];
$const = $graph['Const'];

if ($placeholder['dtype'] === 'int32' && $const['value'] === 0) {
    $graph['feed'] = $placeholder;
    $graph['scalar'] = $const;
}

echo count($graph), PHP_EOL;
--EXPECT--
