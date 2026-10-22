--TEST--
Translated seed (inferredbugs-csharp: kiota/18)
--FILE--
<?php
$decl = [
    'name' => 'parentClass',
    'implements' => [],
    'inherits' => null,
    'usings' => [],
];

$decl['implements'][] = ['name' => 'someInterface'];
$decl['inherits'] = ['name' => 'someInterface'];
$decl['usings'][] = [
    'name' => 'Objects',
    'declaration' => ['name' => 'util', 'isExternal' => true],
];
$decl['usings'][] = [
    'name' => 'project-graph',
    'declaration' => ['name' => 'Message', 'typeDefinition' => ['name' => 'Message']],
];
$decl['implements'][] = ['name' => 'test'];

$out = '';
$out .= "class " . $decl['name'];
if ($decl['inherits'] !== null) {
    $out .= " < " . $decl['inherits']['name'];
}
$out .= "\n";
foreach ($decl['usings'] as $u) {
    $out .= "require ";
    $out .= !empty($u['declaration']['isExternal']) ? $u['declaration']['name'] : "relative " . strtolower($u['declaration']['name']);
    $out .= "\n";
}
foreach ($decl['implements'] as $i) {
    $out .= "include " . $i['name'] . "\n";
}
echo $out;
?>
--EXPECT--
