--TEST--
Translated seed (inferredbugs-csharp: MMALSharp/1)
--FILE--
<?php
$input = ['format' => ['encoding' => null], 'bufferNumRecommended' => 4, 'bufferNumMin' => 2, 'bufferSizeRecommended' => 8192, 'bufferSizeMin' => 4096];
$output = $input;

$output['format']['encoding'] = 'JPEG';
$output['bufferNum'] = max($output['bufferNumRecommended'], $output['bufferNumMin']);
$output['bufferSize'] = max($output['bufferSizeRecommended'], $output['bufferSizeMin']);
$output['jpeg_q_factor'] = 90;

$bufferPool = ['output' => $output];
$buffer = ['allocSize' => 1024, 'length' => 512, 'offset' => 0, 'properties' => ['key' => 'value']];

echo "Create pool\n";
echo "Inside encoder buffer callback\n";
echo "Buffer alloc size " . $buffer['allocSize'] . "\n";
echo "Buffer length " . $buffer['length'] . "\n";
echo "Buffer offset " . $buffer['offset'] . "\n";
var_dump($buffer['properties']);

$output['format']['encoding'] = 'JPEG';
$output['bufferNum'] = max($output['bufferNumRecommended'], $output['bufferNumMin']);
$output['bufferSize'] = max($output['bufferSizeRecommended'], $output['bufferSizeMin']);
$output['jpeg_q_factor'] = 90;

echo "Create pool\n";
$bufferPool = null;
$bufferPool = ['output' => $output];
?>
--EXPECT--
