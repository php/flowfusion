--TEST--
Translated seed (inferredbugs-csharp: kiota/27)
--FILE--
<?php
$parentClass = new stdClass();
$parentClass->name = 'parentClass';
$implements = [];
$inherits = null;
$usings = [];

$usings[] = ['name' => 'Objects', 'declaration' => ['name' => 'util', 'isExternal' => true]];
$usings[] = ['name' => 'project-graph', 'declaration' => ['name' => 'Message', 'typeDefinition' => 'Message']];

$out = '';
$out .= "require 'util'\n";
$out .= "require_relative 'message'\n";
$out .= "class {$parentClass->name}";
if ($inherits !== null) {
    $out .= " < {$inherits['name']}";
}
$out .= "\n";
foreach ($implements as $impl) {
    $out .= "include {$impl['name']}\n";
}
$out .= "end\n";

echo $out;
?>
--EXPECT--
