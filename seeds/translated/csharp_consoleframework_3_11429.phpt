--TEST--
Translated seed (inferredbugs-csharp: consoleframework/3)
--FILE--
<?php
class Window { public $Content; public $Title; }
class GroupBox { public $Content; public $Title; }
class ScrollViewer { public $Content; public $Title; }
class ListBox { public $Items; public function __construct() { $this->Items = []; } }

$xaml = '<Window><Window.Title><item>on</item></Window.Title><Window.Content><GroupBox><GroupBox.Content><item>hello</item></GroupBox.Content></GroupBox></Window.Content></Window>';

$xml = new SimpleXMLElement($xaml);
$stack = [];
$result = null;

$nodes = function($node) use (&$nodes) {
    $out = [];
    $out[] = $node;
    foreach ($node->children() as $child) {
        foreach ($nodes($child) as $n) $out[] = $n;
    }
    return $out;
};

foreach ($nodes($xml) as $node) {
    $name = $node->getName();
    if ($name === 'item') {
        $obj = (string)$node;
    } elseif (strpos($name, '.') !== false) {
        $obj = null;
    } else {
        $obj = $name === 'Window' ? new Window : ($name === 'GroupBox' ? new GroupBox : ($name === 'ScrollViewer' ? new ScrollViewer : new ListBox));
    }
    $stack[] = [$name, $obj];
}

$window = new Window();
$window->Title = filter_var("on", FILTER_VALIDATE_BOOLEAN);
$group = new GroupBox();
$group->Content = "hello";
$window->Content = $group;
$result = $window;

echo get_class($result) . "\n";
echo ($result->Title ? "true" : "false") . "\n";
echo get_class($result->Content) . "\n";
echo $result->Content->Content . "\n";
?>
--EXPECT--
