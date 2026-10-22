--TEST--
Translated seed (inferredbugs-csharp: kiota/2)
--FILE--
<?php
$cases = [
    ['implements' => false, 'extends' => false, 'imports' => []],
    ['implements' => true, 'extends' => false, 'imports' => []],
    ['implements' => false, 'extends' => true, 'imports' => []],
    ['implements' => false, 'extends' => false, 'imports' => [
        ['name' => 'Objects', 'decl' => ['name' => 'util', 'external' => true]],
        ['name' => 'graph', 'decl' => ['name' => 'Message', 'external' => false]],
    ]],
    ['implements' => false, 'extends' => false, 'imports' => [
        ['name' => 'graph', 'decl' => ['name' => 'Message', 'ns' => 'graph/messages']],
    ]],
    ['implements' => false, 'extends' => false, 'imports' => [
        ['name' => 'graph', 'decl' => ['name' => 'Message', 'ns' => 'messages']],
    ]],
    ['implements' => false, 'extends' => false, 'imports' => [
        ['name' => 'graph', 'decl' => ['name' => 'Message', 'ns' => 'graph']],
    ]],
];

foreach ($cases as $case) {
    $out = '';
    if ($case['imports']) {
        foreach ($case['imports'] as $imp) {
            $decl = $imp['decl'];
            $path = isset($decl['external']) && $decl['external'] ? "'{$decl['name']}'" : (
                isset($decl['ns']) ? (
                    $decl['ns'] === 'graph/messages' ? './messages/message' :
                    ($decl['ns'] === 'messages' ? '../messages/message' : './message')
                ) : './message'
            );
            $out .= "import {$imp['name']} from {$path};\n";
        }
    }
    $out .= "export class parentClass";
    if ($case['extends']) $out .= " extends someInterface";
    if ($case['implements']) $out .= " implements someInterface";
    $out .= " {}\n";
    echo $out;
}
?>
--EXPECT--
