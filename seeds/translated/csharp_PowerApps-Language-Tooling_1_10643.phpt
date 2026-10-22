--TEST--
Translated seed (inferredbugs-csharp: PowerApps-Language-Tooling/1)
--FILE--
<?php
$header = "//! PAFile:0.1";
$contents = $header . "\ncontrol root::tmpl\n  a := 1\n  control child::tmpl\n";
if (!str_starts_with($contents, $header)) {
    exit(1);
}
$content = substr($contents, strlen($header) + 1);
$lines = preg_split("/\r\n|\n|\r/", $content);
$stack = [];
$root = null;

foreach ($lines as $line) {
    if ($line === '') continue;
    preg_match('/^(\s*)(.*)$/', $line, $m);
    $indent = strlen($m[1]);
    $text = $m[2];
    while ($stack && end($stack)['indent'] >= $indent) array_pop($stack);
    $parent = $stack ? $stack[count($stack) - 1]['node'] : null;

    if (str_starts_with($text, 'control ')) {
        $rest = substr($text, 8);
        [$nameTpl, $variant] = array_pad(explode(':', $rest, 2), 2, null);
        [$name, $tpl] = array_pad(explode('::', $nameTpl, 2), 2, '');
        $node = ['name' => trim($name), 'template' => trim($tpl), 'variant' => $variant !== null ? trim($variant) : '', 'rules' => [], 'children' => []];
        if ($parent !== null) $parent['children'][] = $node; else $root = $node;
        $stack[] = ['indent' => $indent, 'node' => &$node];
        unset($node);
    } elseif (str_contains($text, ':=')) {
        [$prop, $expr] = array_map('trim', explode(':=', $text, 2));
        if ($parent !== null) $parent['rules'][$prop] = $expr;
    }
}

echo "ok\n";
?>
--EXPECT--
