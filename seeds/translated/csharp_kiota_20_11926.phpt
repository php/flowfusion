--TEST--
Translated seed (inferredbugs-csharp: kiota/20)
--FILE--
<?php
$decl = [
    'name' => 'parentClass',
    'implements' => [],
    'extends' => null,
    'usings' => [],
];

$decl['implements'][] = ['name' => 'someInterface'];
$decl['extends'] = ['name' => 'someInterface'];

$decl['usings'][] = ['name' => 'Objects', 'declaration' => ['name' => 'java.util', 'isExternal' => true]];
$decl['usings'][] = ['name' => 'project.graph', 'declaration' => ['name' => 'Message']];

$out = "public class {$decl['name']}";
if ($decl['extends']) $out .= " extends {$decl['extends']['name']}";
if ($decl['implements']) $out .= " implements " . implode(', ', array_map(fn($t) => $t['name'], $decl['implements']));
$out .= "\n";

foreach ($decl['usings'] as $u) {
    $dn = $u['declaration']['name'] ?? '';
    if ($dn === $decl['name']) continue;
    if (($u['declaration']['isExternal'] ?? false) && $dn === 'java.util') $out .= "import java.util.{$u['name']}\n";
    elseif ($dn !== '') $out .= "import {$u['name']}.{$dn}\n";
}

echo $out;
--EXPECT--
