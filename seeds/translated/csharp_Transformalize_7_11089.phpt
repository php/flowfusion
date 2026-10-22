--TEST--
Translated seed (inferredbugs-csharp: Transformalize/7)
--FILE--
<?php
$json = '{"a":"123","b":true,"c":null,"d":[1,2],"e":{"x":"y"}}';
$dict = json_decode($json, true);
$k = 'a';
$v = $dict[$k];
echo $v, PHP_EOL;
$x = json_decode((string)$json, true);
echo (int)$x['a'], PHP_EOL;
$row = ['src' => $json];
$dict = json_decode((string)$row['src'], true);
$row['out1'] = (string)$dict['a'];
$row['out2'] = (bool)$dict['b'];
$row['out3'] = $dict['c'];
$row['out4'] = $dict['d'][1];
$row['out5'] = $dict['e']['x'];
echo $row['out1'], PHP_EOL;
echo ($row['out2'] ? '1' : '0'), PHP_EOL;
echo var_export($row['out3'], true), PHP_EOL;
echo $row['out4'], PHP_EOL;
echo $row['out5'], PHP_EOL;
?>
--EXPECT--
