--TEST--
Translated seed (inferredbugs-csharp: consoleframework/17)
--FILE--
<?php
class Item {
    public $Value = '';
}
class Root {
    public $Items;
    public $Text = '';
    public function __construct() { $this->Items = []; }
}
$xml = '<Root xmlns:x="http://consoleframework.org/xaml.xsd"><Root.Text>{}abc</Root.Text><Root.Items><item x:Key="k1">one</item><item x:Id="id1">two</item></Root.Items></Root>';
$doc = new DOMDocument();
$doc->loadXML($xml, LIBXML_NONET);
$root = $doc->documentElement;
$out = new Root();
$namespaces = [];
foreach ($root->attributes as $a) {
    if ($a->prefix === 'xmlns') $namespaces[$a->localName] = $a->value;
}
foreach ($root->childNodes as $n) {
    if ($n->nodeType !== XML_ELEMENT_NODE) continue;
    if ($n->tagName === 'Root.Text') {
        $out->Text = substr($n->textContent, 2);
    } elseif ($n->tagName === 'Root.Items') {
        foreach ($n->childNodes as $c) {
            if ($c->nodeType !== XML_ELEMENT_NODE) continue;
            $it = new Item();
            $it->Value = $c->textContent;
            $out->Items[] = $it;
        }
    }
}
echo $out->Text, "\n", count($out->Items), "\n";
?>
--EXPECT--
