--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/24)
--FILE--
<?php
$s = str_repeat('a', 1200);
$max = 1024;

$transaction = ['name' => $s, 'type' => 'test', 'duration' => 1, 'result' => 'fail'];
foreach (['name'] as $k) if (strlen($transaction[$k]) > $max) $transaction[$k] = substr($transaction[$k], 0, $max - 3) . '...';
$j = json_encode($transaction);
$d = json_decode($j, true);
if (strlen($d['name']) !== $max || substr($d['name'], 1021, 3) !== '...' || $d['type'] !== 'test' || $d['result'] !== 'fail') exit(1);

$dummy = ['IntProp' => 42, 'StringProp' => $s, 'DictionaryProp' => ['foo' => $s]];
$j = json_encode($dummy);
$d = json_decode($j, true);
if (strlen($d['StringProp']) !== strlen($s) || $d['StringProp'] !== $s || $d['IntProp'] !== 42 || strlen($d['DictionaryProp']['foo']) !== strlen($s) || $d['DictionaryProp']['foo'] !== $s) exit(1);

$context = ['tags' => ['foo' => $s]];
foreach ($context['tags'] as $k => $v) if (strlen($v) > $max) $context['tags'][$k] = substr($v, 0, $max - 3) . '...';
$j = json_encode($context);
$d = json_decode($j, true);
if (strlen($d['tags']['foo']) !== $max || substr($d['tags']['foo'], 1021, 3) !== '...') exit(1);

$db = ['Statement' => $s];
$j = json_encode($db);
$d = json_decode($j, true);
if (strlen($d['Statement']) !== strlen($s) || $d['Statement'] !== $s) exit(1);
?>
--EXPECT--
