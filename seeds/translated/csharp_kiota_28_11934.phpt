--TEST--
Translated seed (inferredbugs-csharp: kiota/28)
--FILE--
<?php
$decl = new class {
    public string $name = 'parentClass';
    public array $implements = [];
    public ?string $inherits = null;
    public array $usings = [];
};

$decl->implements[] = 'someInterface';
$decl->inherits = 'someInterface';
$decl->usings[] = ['name' => 'Objects', 'decl' => ['name' => 'java.util', 'external' => true]];
$decl->usings[] = ['name' => 'project.graph', 'decl' => ['name' => 'Message', 'external' => false]];
$decl->usings[] = ['name' => 'project.graph', 'decl' => ['name' => 'parentClass', 'external' => false]];

$out = '';
$out .= 'public class ' . $decl->name;
if ($decl->inherits !== null) {
    $out .= ' extends ' . $decl->inherits;
}
if ($decl->implements) {
    $out .= ' implements ' . implode(', ', $decl->implements);
}
$out .= "\n";

$imports = [];
foreach ($decl->usings as $u) {
    $dn = $u['decl']['name'];
    $name = $u['name'];
    if ($dn === $decl->name) {
        continue;
    }
    $imports[] = ($u['decl']['external'] ? $dn . '.' . $name : $dn . '.' . $name);
}
$imports = array_values(array_unique($imports));
sort($imports);
foreach ($imports as $i) {
    $out .= 'import ' . $i . ";\n";
}

echo $out;
?>
--EXPECT--
