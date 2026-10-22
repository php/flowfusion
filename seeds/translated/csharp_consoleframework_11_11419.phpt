--TEST--
Translated seed (inferredbugs-csharp: consoleframework/11)
--FILE--
<?php
class Item { public $Content; }
class GroupBox { public $Content; }
class ScrollViewer { public $Content; }
class ListBox { public $Content; }
class Window { public $Content; public $Title; public $Visible; }

$xaml = '<Window Title="Main" Visible="true"><GroupBox><item>hello</item><item>world</item></GroupBox></Window>';

$xml = new SimpleXMLElement($xaml);
$stack = [];
$result = null;

foreach ($xml->xpath('descendant-or-self::*') as $node) {
    $name = $node->getName();
    $parent = $node->xpath('parent::*');
    $parent = $parent ? $parent[0]->getName() : null;

    if ($name === 'item') {
        $obj = (string)$node;
    } elseif ($name === 'Window') {
        $obj = new Window();
        foreach ($node->attributes() as $k => $v) {
            if ($k === 'Visible') $obj->$k = filter_var((string)$v, FILTER_VALIDATE_BOOLEAN);
            else $obj->$k = (string)$v;
        }
    } elseif ($name === 'GroupBox') {
        $obj = new GroupBox();
    } elseif ($name === 'ScrollViewer') {
        $obj = new ScrollViewer();
    } elseif ($name === 'ListBox') {
        $obj = new ListBox();
    } else {
        continue;
    }

    $stack[] = [$name, $obj];
    while (count($stack) > 1) {
        $child = array_pop($stack);
        $top = &$stack[count($stack) - 1][1];
        if (!isset($top->Content)) $top->Content = [];
        if (is_array($top->Content)) $top->Content[] = $child[1];
        else $top->Content = $child[1];
        break;
    }

    if ($name === 'Window') $result = $obj;
}

if ($result instanceof Window && $result->Content instanceof GroupBox && is_array($result->Content->Content) && count($result->Content->Content) === 2) {
    $result->Content->Content[0] = (string)$result->Content->Content[0];
    $result->Content->Content[1] = (string)$result->Content->Content[1];
}
?>
--EXPECT--
