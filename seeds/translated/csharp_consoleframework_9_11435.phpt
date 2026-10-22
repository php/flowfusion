--TEST--
Translated seed (inferredbugs-csharp: consoleframework/9)
--FILE--
<?php
class Window { public $Content; public $Title = ''; }
class GroupBox { public $Content; public $Header = ''; }
class ScrollViewer { public $Content; }
class ListBox { public $Items; public function __construct() { $this->Items = []; } }

$xaml = '<Window Title="main"><GroupBox Header="h"><item>hello</item></GroupBox><ListBox><item>one</item><item>two</item></ListBox></Window>';

$xml = new SimpleXMLElement($xaml);
$stack = [];
$result = null;

$convert = function ($source, $dest, $value) {
    if ($dest === $source || is_a($value, $dest)) return $value;
    if ($dest === 'bool' && is_string($value)) return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    if (enum_exists($dest) && is_string($value)) return constant($dest . '::' . $value);
    return $value;
};

$walk = function ($node) use (&$walk, &$stack, &$result, $convert) {
    $name = $node->getName();
    if ($name === 'item') {
        $obj = (string)$node;
    } else {
        $obj = match ($name) {
            'Window' => new Window(),
            'GroupBox' => new GroupBox(),
            'ScrollViewer' => new ScrollViewer(),
            'ListBox' => new ListBox(),
            default => null,
        };
        foreach ($node->attributes() as $k => $v) $obj->$k = (string)$v;
        foreach ($node->children() as $child) {
            $childName = $child->getName();
            if ($childName === 'item') {
                $obj->Content = (string)$child;
            } else {
                $walk($child);
                $childObj = array_pop($stack);
                if ($name === 'ListBox' && $childObj !== null) $obj->Items[] = $convert(get_debug_type($childObj), 'mixed', $childObj);
                else $obj->Content = $childObj;
            }
        }
    }
    $stack[] = $obj;
    if (count($stack) === 1) $result = $obj;
};

$walk($xml);
echo is_object($result) ? get_class($result) : (string)$result;
?>
--EXPECT--
