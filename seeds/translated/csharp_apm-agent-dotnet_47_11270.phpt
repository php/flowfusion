--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/47)
--FILE--
<?php
$tx = [
    'name' => 'TestTransaction',
    'type' => 'UnitTest',
    'result' => 'success',
    'tags' => ['fooTransaction1' => 'barTransaction1', 'fooTransaction2' => 'barTransaction2'],
    'spans' => []
];

$span = [
    'name' => 'TestSpan',
    'type' => 'db',
    'subtype' => 'mssql',
    'action' => 'query',
    'tags' => ['fooSpan1' => 'barSpan1', 'fooSpan2' => 'barSpan2']
];

usleep(5000);

$span['duration'] = 5;
$tx['spans'][] = $span;
$tx['duration'] = 5;

$errors = [];
try {
    throw new InvalidArgumentException('Foo!');
} catch (Throwable $e) {
    $errors[] = ['message' => $e->getMessage(), 'culprit' => 'PublicAPI-CaptureException'];
}

echo $tx['name'], "\n";
echo $tx['type'], "\n";
echo $tx['result'], "\n";
echo $tx['spans'][0]['name'], "\n";
echo $tx['spans'][0]['type'], "\n";
echo $tx['spans'][0]['subtype'], "\n";
echo $tx['spans'][0]['action'], "\n";
echo $tx['tags']['fooTransaction1'], "\n";
echo $tx['tags']['fooTransaction2'], "\n";
echo $tx['spans'][0]['tags']['fooSpan1'], "\n";
echo $tx['spans'][0]['tags']['fooSpan2'], "\n";
echo $errors[0]['message'], "\n";
echo $errors[0]['culprit'], "\n";
?>
--EXPECT--
