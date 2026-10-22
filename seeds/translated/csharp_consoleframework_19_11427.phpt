--TEST--
Translated seed (inferredbugs-csharp: consoleframework/19)
--FILE--
<?php
class Node {
    public $child;
    public $items;
    public $map;
    public $value;
    public function __construct() {
        $this->items = [];
        $this->map = [];
    }
}

$xml = '<root xmlns:x="urn:test" x:Id="a"><child><value>{}text</value></child><items><item>1</item><item>2</item></items><map><entry x:Key="k1">v1</entry><entry x:Key="k2">v2</entry></map></root>';
$root = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

$nodes = [];
$walk = function($el) use (&$walk, &$nodes) {
    $name = $el->getName();
    $n = new Node();
    $nodes[] = $n;
    foreach ($el->attributes('x', true) as $k => $v) {
        if ($k === 'Id') $n->id = (string)$v;
        if ($k === 'Key') $n->key = (string)$v;
    }
    $text = trim((string)$el);
    if ($text !== '') $n->value = (substr($text, 0, 2) === '{}' ? substr($text, 2) : $text);
    foreach ($el->children() as $child) {
        $cn = $walk($child);
        $cnName = $child->getName();
        if ($cnName === 'item') $n->items[] = $cn->value;
        elseif ($cnName === 'entry') $n->map[$cn->key] = $cn->value;
        else $n->child = $cn;
    }
    return $n;
};

$result = $walk($root);
echo $result->child->value . "\n";
echo implode(',', $result->items) . "\n";
echo $result->map['k1'] . ':' . $result->map['k2'] . "\n";
?>
--EXPECT--
