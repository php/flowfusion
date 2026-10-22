--TEST--
Translated seed (inferredbugs-csharp: bUnit/5)
--FILE--
<?php
$actual = "<div><span>1</span><br/></div>";
$expected = "<div><span>1</span><br></div>";
$p = new DOMDocument();
$p->preserveWhiteSpace = false;
$p->formatOutput = false;
@$p->loadHTML('<?xml encoding="UTF-8">' . $actual, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
$a = $p->saveHTML();
$q = new DOMDocument();
$q->preserveWhiteSpace = false;
$q->formatOutput = false;
@$q->loadHTML('<?xml encoding="UTF-8">' . $expected, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
$b = $q->saveHTML();
if ($a !== $b) {
    $r = new DOMDocument();
    @$r->loadHTML('<?xml encoding="UTF-8">' . $actual, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $s = new DOMDocument();
    @$s->loadHTML('<?xml encoding="UTF-8">' . $expected, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $a = $r->saveHTML();
    $b = $s->saveHTML();
}
?>
--EXPECT--
