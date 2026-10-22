--TEST--
Translated seed (inferredbugs-csharp: bUnit/3)
--FILE--
<?php
$actual = "<div a='1' b='2'>x</div>";
$expected = "<div b='2' a='1'>x</div>";

$pa = new DOMDocument();
$pb = new DOMDocument();
libxml_use_internal_errors(true);
$pa->loadHTML($actual, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
$pb->loadHTML($expected, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
libxml_clear_errors();

function canon($node) {
    if ($node->nodeType === XML_TEXT_NODE) return trim($node->nodeValue);
    if ($node->nodeType !== XML_ELEMENT_NODE) return '';
    $attrs = [];
    if ($node->attributes) {
        foreach ($node->attributes as $a) $attrs[$a->nodeName] = $a->nodeValue;
        ksort($attrs);
    }
    $s = '<' . $node->nodeName;
    foreach ($attrs as $k => $v) $s .= ' ' . $k . '="' . htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '"';
    $s .= '>';
    foreach ($node->childNodes as $c) {
        $t = canon($c);
        if ($t !== '') $s .= $t;
    }
    $s .= '</' . $node->nodeName . '>';
    return $s;
}

echo canon($pa->documentElement) === canon($pb->documentElement) ? "" : "";
?>
--EXPECT--
