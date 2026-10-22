--TEST--
Translated seed (inferredbugs-csharp: resin/26)
--FILE--
<?php
$base = getcwd();
$path = $base . DIRECTORY_SEPARATOR . 'App_Data';
$tokenizer = new stdClass();
$services = [];
$services[] = ['type' => 'LocalStorageSessionFactory', 'instance' => ['path' => $path, 'tokenizer' => $tokenizer]];
$services[] = ['type' => 'ITokenizer', 'instance' => $tokenizer];
echo count($services) . PHP_EOL;
--EXPECT--
