--TEST--
Translated seed (inferredbugs-csharp: kiota/24)
--FILE--
<?php
$decl = [
    'name' => 'parentClass',
    'implements' => [],
    'inherits' => null,
    'usings' => [],
];

$emit = function (array $d): string {
    $out = '';
    $imports = [];
    foreach ($d['usings'] as $u) {
        $imports[$u['decl']['name']] = $u['decl']['isExternal'] ? $u['decl']['name'] : ucfirst($u['decl']['name']);
    }
    ksort($imports);
    foreach ($imports as $name) {
        $out .= "using $name;\n";
    }
    $out .= 'public class ' . $d['name'];
    $parts = [];
    if ($d['inherits']) {
        $parts[] = $d['inherits']['name'];
    }
    foreach ($d['implements'] as $i) {
        $parts[] = $i['name'];
    }
    if ($parts) {
        $out .= ' : ' . implode(', ', $parts);
    }
    return $out;
};

echo $emit($decl), "\n";

$decl['implements'][] = ['name' => 'someInterface'];
echo $emit($decl), "\n";

$decl['implements'] = [];
$decl['inherits'] = ['name' => 'someInterface'];
echo $emit($decl), "\n";

$decl['inherits'] = null;
$decl['usings'] = [
    ['name' => 'Objects', 'decl' => ['name' => 'system.util', 'isExternal' => true]],
    ['name' => 'project.graph', 'decl' => ['name' => 'Message', 'isExternal' => false]],
];
echo $emit($decl), "\n";
?>
--EXPECT--
