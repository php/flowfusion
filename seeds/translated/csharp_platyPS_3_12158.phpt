--TEST--
Translated seed (inferredbugs-csharp: platyPS/3)
--FILE--
<?php
$nodes = [
    ['type' => 'heading', 'level' => 2, 'text' => 'Command'],
    ['type' => 'heading', 'level' => 3, 'text' => 'description'],
    ['type' => 'paragraph', 'text' => 'Text'],
];

$i = 0;
$out = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<helpItems schema=\"maml\">\n<command:command xmlns:maml=\"http://schemas.microsoft.com/maml/2004/10\" xmlns:command=\"http://schemas.microsoft.com/maml/dev/command/2004/10\" xmlns:dev=\"http://schemas.microsoft.com/maml/dev/2004/10\" xmlns:MSHelp=\"http://msdn.microsoft.com/mshelp\">\n";
$stack = [];

while ($i < count($nodes)) {
    $n = $nodes[$i++];
    if ($n['type'] === 'heading' && $n['level'] === 2) {
        while ($stack) {
            $t = array_pop($stack);
            $out .= "</$t>\n";
        }
        $out .= "<command:details>\n";
        $stack[] = 'command:details';
        $out .= "<command:name>{$n['text']}</command:name>";
        $d = $nodes[$i++] ?? null;
        if (!$d || $d['type'] !== 'heading' || $d['level'] !== 3 || strcasecmp($d['text'], 'DESCRIPTION') !== 0) {
            break;
        }
        $p = $nodes[$i++] ?? null;
        if (!$p || $p['type'] !== 'paragraph') {
            break;
        }
        $out .= "<maml:description>\n<maml:para>\n{$p['text']}\n</maml:para>\n</maml:description>\n";
        array_pop($stack);
        $out .= "</command:details>\n";
    }
}

$out .= "</command:command>\n</helpItems>\n";
echo $out;
?>
--EXPECT--
