--TEST--
Translated seed (inferredbugs-csharp: kiota/26)
--FILE--
<?php
$decl = [
    'name' => 'parentClass',
    'implements' => [],
    'extends' => null,
    'usings' => [],
];

$decl['implements'][] = 'someInterface';
$decl['extends'] = 'someInterface';
$decl['usings'][] = ['name' => 'Objects', 'module' => 'util', 'external' => true];

$out = '';
$out .= 'import { Objects } from \'util\';' . PHP_EOL;
$out .= 'export class ' . $decl['name'] . ' extends ' . $decl['extends'] . ' implements ' . implode(', ', $decl['implements']) . ' {' . PHP_EOL;
$out .= '}' . PHP_EOL;

echo $out;
?>
--EXPECT--
