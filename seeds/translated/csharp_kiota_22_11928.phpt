--TEST--
Translated seed (inferredbugs-csharp: kiota/22)
--FILE--
<?php
$decl = (object)[
    'name' => 'parentClass',
    'implements' => [],
    'inherits' => null,
    'usings' => [],
];

$decl->implements[] = (object)['name' => 'someInterface'];
$decl->inherits = (object)['name' => 'someInterface'];
$decl->usings[] = (object)[
    'name' => 'Objects',
    'declaration' => (object)['name' => 'java.util', 'isExternal' => true],
];
$decl->usings[] = (object)[
    'name' => 'project.graph',
    'declaration' => (object)['name' => 'Message'],
];
$decl->usings[] = (object)[
    'name' => 'project.graph',
    'declaration' => (object)['name' => 'parentClass'],
];

$out = "public class {$decl->name}";
if ($decl->inherits) {
    $out .= " extends {$decl->inherits->name}";
}
if ($decl->implements) {
    $out .= " implements " . implode(', ', array_map(fn($t) => $t->name, $decl->implements));
}
$out .= "\n";

$imports = [];
foreach ($decl->usings as $u) {
    $target = $u->declaration->name . '.' . $u->name;
    if ($u->name !== $decl->name) {
        $imports[$target] = true;
    }
}
foreach (array_keys($imports) as $i) {
    $out .= "import $i\n";
}

echo $out;
?>
--EXPECT--
