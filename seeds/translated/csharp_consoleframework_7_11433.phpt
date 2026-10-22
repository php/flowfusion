--TEST--
Translated seed (inferredbugs-csharp: consoleframework/7)
--FILE--
<?php
class Window { public $Content; public $Title; public function __construct() {} }
class GroupBox { public $Content; public function __construct() {} }
class ScrollViewer { public $Content; public function __construct() {} }
class ListBox { public $Items = []; public function __construct() {} }

$xaml = '<Window Title="main"><GroupBox><item>hello</item><item>world</item></GroupBox></Window>';

$xml = new SimpleXMLElement($xaml);
$stack = [];
$result = null;

$walk = function($node, $parent = null, $parentProp = null) use (&$stack, &$result, &$walk) {
    $name = $node->getName();
    if ($name === 'item') {
        $value = (string)$node;
    } else {
        switch ($name) {
            case 'Window': $value = new Window(); break;
            case 'GroupBox': $value = new GroupBox(); break;
            case 'ScrollViewer': $value = new ScrollViewer(); break;
            case 'ListBox': $value = new ListBox(); break;
            default: $value = null;
        }
        if ($value !== null) {
            foreach ($node->attributes() as $k => $v) $value->$k = (string)$v;
            foreach ($node->children() as $child) {
                $childName = $child->getName();
                if ($childName === 'item') {
                    if (!isset($value->Content)) $value->Content = [];
                    $value->Content[] = (string)$child;
                } else {
                    $childValue = $walk($child, $value, 'Content');
                    if (is_array($value->Content ?? null)) $value->Content[] = $childValue;
                    else $value->Content = $childValue;
                }
            }
        }
    }
    return $value;
};

$result = $walk($xml);
echo is_object($result) ? get_class($result) : gettype($result);
?>
--EXPECT--
