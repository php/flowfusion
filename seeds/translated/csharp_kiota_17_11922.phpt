--TEST--
Translated seed (inferredbugs-csharp: kiota/17)
--FILE--
<?php
$parentClass = [
    'name' => 'parentClass',
    'implements' => [],
    'inherits' => null,
    'usings' => [],
];

$declaration = &$parentClass;

$declaration['implements'][] = ['name' => 'someInterface'];
$out = "class {$declaration['name']}\n  include someInterface\nend\n";
echo $out;
if (strpos($out, 'include') === false) exit(1);

$declaration['inherits'] = ['name' => 'someInterface'];
$out = "class {$declaration['name']} < {$declaration['inherits']['name']}\nend\n";
echo $out;
if (strpos($out, '<') === false) exit(1);

$declaration['usings'][] = ['name' => 'Objects', 'declaration' => ['name' => 'util', 'isExternal' => true]];
$declaration['usings'][] = ['name' => 'project-graph', 'declaration' => ['name' => 'Message', 'typeDefinition' => ['name' => 'Message']]];
$out = "require 'util'\nrequire_relative 'message'\nclass {$declaration['name']}\nend\n";
echo $out;
if (strpos($out, 'require_relative') === false || strpos($out, 'message') === false || strpos($out, 'require') === false) exit(1);

$declaration['implements'][] = ['name' => 'test'];
$out = "class {$declaration['name']}\n  include test\nend\n";
echo $out;
if (strpos($out, 'include') === false || strpos($out, 'test') === false) exit(1);
?>
--EXPECT--
