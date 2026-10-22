--TEST--
Translated seed (inferredbugs-csharp: consoleframework/10)
--FILE--
<?php
class Window { public $Content; public $Title; }
class GroupBox { public $Content; public $Header; }
class ScrollViewer { public $Content; }
class ListBox { public $Items; public function __construct(){ $this->Items = []; } }
$xaml = '<Window Title="main"><Window.Content><GroupBox Header="h"><GroupBox.Content><ListBox><ListBox.Items><item>a</item><item>b</item></ListBox.Items></ListBox></GroupBox.Content></GroupBox></Window.Content></Window>';
$xml = simplexml_load_string($xaml);
$stack = [];
$root = null;
$walk = function($node) use (&$walk, &$stack, &$root) {
    $name = $node->getName();
    if ($name === 'item') {
        $obj = (string)$node;
    } else {
        $obj = new $name();
        foreach ($node->attributes() as $k => $v) $obj->$k = (string)$v;
        $stack[] = [$name, $obj];
        foreach ($node->children() as $child) $walk($child);
        array_pop($stack);
    }
    if ($stack) {
        $i = count($stack) - 1;
        if ($name === 'item') {
            $parent = $stack[$i][1];
            $parent->Items[] = $obj;
        } else if ($node->count() === 0 && trim((string)$node) !== '') {
            $parent = $stack[$i][1];
            $parent->Content = $obj;
        } else if ($i > 0) {
            $parent = $stack[$i - 1][1];
            $prop = $stack[$i - 1][0] === 'Window' && $name === 'GroupBox' ? 'Content' : 'Content';
            $parent->$prop = $obj;
        } else {
            $root = $obj;
        }
    } else {
        $root = $obj;
    }
};
$walk($xml);
echo is_object($root) ? get_class($root) : $root;
?>
--EXPECT--
