--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/57)
--FILE--
<?php
$s = str_repeat('a', 1200);

$transaction = [
    'name' => substr($s, 0, 1021) . '...',
    'type' => 'test',
    'duration' => 1,
    'result' => 'fail',
];
$json = json_encode($transaction, JSON_UNESCAPED_SLASHES);
$decoded = json_decode($json, true);

$dummy = [
    'intProp' => 42,
    'stringProp' => $s,
    'dictionaryProp' => ['foo' => $s],
];
$json = json_encode($dummy, JSON_UNESCAPED_SLASHES);
$decodedDummy = json_decode($json, true);

$context = ['tags' => ['foo' => substr($s, 0, 1021) . '...']];
$json = json_encode($context, JSON_UNESCAPED_SLASHES);
$decodedContext = json_decode($json, true);

$db = ['statement' => $s];
$json = json_encode($db, JSON_UNESCAPED_SLASHES);
$decodedDb = json_decode($json, true);

if (strlen($decoded['name']) !== 1024 || substr($decoded['name'], 1021, 3) !== '...' || $decoded['type'] !== 'test' || $decoded['result'] !== 'fail') exit(1);
if (strlen($decodedDummy['stringProp']) !== 1200 || $decodedDummy['stringProp'] !== $s || $decodedDummy['intProp'] !== 42) exit(1);
if (strlen($decodedContext['tags']['foo']) !== 1024 || substr($decodedContext['tags']['foo'], 1021, 3) !== '...') exit(1);
if (strlen($decodedDummy['dictionaryProp']['foo']) !== 1200 || $decodedDummy['dictionaryProp']['foo'] !== $s) exit(1);
if (strlen($decodedDb['statement']) !== 1200 || $decodedDb['statement'] !== $s) exit(1);
?>
--EXPECT--
