--TEST--
Translated seed (inferredbugs-csharp: bUnit/2)
--FILE--
<?php
$actual = "<div id='x' a='1' b='2'>Hi</div>";
$expected = "<div b='2' a='1' id='x'>Hi</div>";

$pa = new DOMDocument();
$pb = new DOMDocument();
libxml_use_internal_errors(true);
$pa->loadHTML($actual, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
$pb->loadHTML($expected, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

$na = $pa->documentElement;
$nb = $pb->documentElement;

$sa = $na->getAttribute('a');
$na->setAttribute('a', $na->getAttribute('b'));
$na->setAttribute('b', $sa);

if ($na->C14N() !== $nb->C14N()) {
    throw new Exception("markup mismatch");
}
?>
--EXPECT--
