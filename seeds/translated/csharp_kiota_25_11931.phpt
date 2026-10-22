--TEST--
Translated seed (inferredbugs-csharp: kiota/25)
--FILE--
<?php
$decl = (object)[
    'name' => 'parentClass',
    'implements' => [],
    'inherits' => null,
    'usings' => [],
];
$parts = [];
$parts[] = 'using System;';
$parts[] = 'using Project.Graph;';
$parts[] = 'public class ' . $decl->name . ' : someInterface, someBase';
$parts[] = 'using System.Util;';
echo implode("\n", $parts), "\n";
?>
--EXPECT--
