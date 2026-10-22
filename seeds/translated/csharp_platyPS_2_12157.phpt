--TEST--
Translated seed (inferredbugs-csharp: platyPS/2)
--FILE--
<?php
$nodes = [
    ['type' => 'heading', 'level' => 2, 'text' => 'Command'],
    ['type' => 'heading', 'level' => 3, 'text' => 'DESCRIPTION'],
    ['type' => 'paragraph', 'text' => 'Text'],
    ['type' => 'heading', 'level' => 2, 'text' => 'Next'],
    ['type' => 'heading', 'level' => 3, 'text' => 'description'],
    ['type' => 'paragraph', 'text' => 'More text'],
];

$tagStack = [];
$out = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<helpItems schema=\"maml\">\n";
$out .= "<command:command xmlns:maml=\"http://schemas.microsoft.com/maml/2004/10\" xmlns:command=\"http://schemas.microsoft.com/maml/dev/command/2004/10\" xmlns:dev=\"http://schemas.microsoft.com/maml/dev/2004/10\" xmlns:MSHelp=\"http://msdn.microsoft.com/mshelp\">\n";

for ($i = 0, $n = count($nodes); $i < $n; $i++) {
    $node = $nodes[$i];
    if ($node['type'] === 'heading' && $node['level'] === 2) {
        while ($tagStack) {
            $tag = array_pop($tagStack);
            $out .= "</$tag>\n";
        }
        $out .= "<command:details>\n";
        $tagStack[] = 'command:details';
        $out .= "<command:name>{$node['text']}</command:name>";
        $node = $nodes[++$i] ?? null;
        if (!$node || $node['type'] !== 'heading' || $node['level'] !== 3 || strcasecmp($node['text'], 'DESCRIPTION') !== 0) {
            continue;
        }
        $node = $nodes[++$i] ?? null;
        if (!$node || $node['type'] !== 'paragraph') {
            continue;
        }
        $out .= "<maml:description>\n";
        $out .= "<maml:para>\n";
        $out .= $node['text'] . "\n";
        $out .= "</maml:para>\n";
        $out .= "</maml:description>\n";
        array_pop($tagStack);
        $out .= "</command:details>\n";
    }
}

$out .= "</command:command>\n";
$out .= "</helpItems>\n";
echo $out;
?>
--EXPECT--
