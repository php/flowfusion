--TEST--
Translated seed (inferredbugs-csharp: kiota/19)
--FILE--
<?php
$declaration = [
    'name' => 'parentClass',
    'implements' => [],
    'inherits' => null,
    'usings' => []
];

$declaration['implements'][] = ['name' => 'test'];
$declaration['inherits'] = ['name' => 'someInterface'];
$declaration['usings'][] = [
    'name' => 'Objects',
    'declaration' => ['name' => 'util', 'isExternal' => true]
];
$declaration['usings'][] = [
    'name' => 'project-graph',
    'declaration' => ['name' => 'Message', 'typeDefinition' => ['name' => 'Message']]
];

$out = '';
$out .= "class " . $declaration['name'] . "\n";
if ($declaration['inherits']) {
    $out .= "  < " . $declaration['inherits']['name'] . "\n";
}
foreach ($declaration['usings'] as $using) {
    $out .= "  require " . $using['name'] . "\n";
    if (!empty($using['declaration']['isExternal'])) {
        $out .= "  require_relative " . $using['declaration']['name'] . "\n";
    } else {
        $out .= "  require " . strtolower($using['declaration']['name']) . "\n";
    }
}
foreach ($declaration['implements'] as $impl) {
    $out .= "  include " . $impl['name'] . "\n";
}
echo $out;
?>
--EXPECT--
