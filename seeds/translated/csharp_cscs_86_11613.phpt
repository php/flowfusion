--TEST--
Translated seed (inferredbugs-csharp: cscs/86)
--FILE--
<?php
$map = [];
$map['x'] = ['a' => 1, 'b' => 2];
$map['x']['c'] = 3;
$map['y'] = $map['x'];
$map['y']['a'] += 10;
$map['z'] = [];
$map['z'][2] = 'v';
$map['z'][] = 'w';
$map['z']['k'] = 'h';
$map['z']['k'] .= '2';
$map['n'] = 1;
$map['n']++;
--$map['n'];
$map['s'] = "12";
$map['s'] += 3;
$map['t'] = "a";
$map['t'] .= 5;
$map['u'] = $map['z'];
unset($map['u'][2]);
$map['u'][] = 'q';
$map['r'] = ['p' => ['q' => 7]];
$ref =& $map['r']['p'];
$ref['q'] *= 2;
$ref['new'] = 9;
echo json_encode($map, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
?>
--EXPECT--
