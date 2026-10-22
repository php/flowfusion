--TEST--
Translated seed (inferredbugs-csharp: xms/1)
--FILE--
<?php
$exception = new Exception('x');
$dependents = ['a' => 1, 'b' => [2, 3], 'c' => null];
$json = json_encode($dependents, JSON_THROW_ON_ERROR);
$body = fopen('php://temp', 'r+');
fwrite($body, $json);
rewind($body);
$path = '/error/dependentexception';
echo stream_get_contents($body);
echo "\n";
echo $path;
?>
--EXPECT--
