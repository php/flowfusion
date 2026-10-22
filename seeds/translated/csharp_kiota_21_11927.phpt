--TEST--
Translated seed (inferredbugs-csharp: kiota/21)
--FILE--
<?php
declare(strict_types=1);

$declaration = [
    'name' => 'parentClass',
    'implements' => [],
    'extends' => null,
    'usings' => [],
];

$declaration['implements'][] = 'someInterface';
$declaration['extends'] = 'someInterface';
$declaration['usings'][] = ['name' => 'Objects', 'decl' => ['name' => 'java.util', 'external' => true]];
$declaration['usings'][] = ['name' => 'project.graph', 'decl' => ['name' => 'Message', 'external' => false]];
$declaration['usings'][] = ['name' => 'project.graph', 'decl' => ['name' => 'parentClass', 'external' => false]];

$out = '';

$imports = [];
foreach ($declaration['usings'] as $u) {
    $full = $u['decl']['name'] . '.' . $u['name'];
    if ($u['decl']['name'] === $declaration['name']) {
        continue;
    }
    $imports[$full] = true;
}

foreach (array_keys($imports) as $imp) {
    $out .= "import " . str_replace('.', '\\', $imp) . ";\n";
}

$out .= "public class " . $declaration['name'];
if ($declaration['extends'] !== null) {
    $out .= " extends " . $declaration['extends'];
}
if ($declaration['implements']) {
    $out .= " implements " . implode(', ', $declaration['implements']);
}
$out .= " {}\n";

echo $out;
--EXPECT--
