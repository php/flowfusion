--TEST--
Translated seed (inferredbugs-csharp: kiota/30)
--FILE--
<?php
$decl = [
    'name' => 'parentClass',
    'implements' => [],
    'inherits' => null,
    'usings' => [],
];

function render(array $decl): string {
    $imports = [];
    foreach ($decl['usings'] as $u) {
        $d = $u['declaration'];
        if (!empty($d['isExternal'])) {
            $imports[] = 'using ' . str_replace('.', '\\', $d['name']) . ';';
        } else {
            $imports[] = 'using ' . str_replace('.', '\\', $u['name']) . ';';
        }
    }
    $header = 'public class ' . $decl['name'];
    $parts = [];
    if ($decl['inherits']) $parts[] = $decl['inherits']['name'];
    foreach ($decl['implements'] as $i) $parts[] = $i['name'];
    if ($parts) $header .= ' : ' . implode(', ', $parts);
    return implode("\n", $imports) . "\n" . $header . "\n";
}

echo render($decl);

$decl['implements'][] = ['name' => 'someInterface'];
echo render($decl);

$decl['implements'] = [];
$decl['inherits'] = ['name' => 'someInterface'];
echo render($decl);

$decl['inherits'] = null;
$decl['usings'] = [
    ['name' => 'Objects', 'declaration' => ['name' => 'system.util', 'isExternal' => true]],
    ['name' => 'project.graph', 'declaration' => ['name' => 'Message']],
];
echo render($decl);
?>
--EXPECT--
