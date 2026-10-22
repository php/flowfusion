--TEST--
Translated seed (inferredbugs-csharp: ReadableExpressions/1)
--FILE--
<?php
$translation = "<span class=\"kw\">if</span> &amp; <span class=\"nm\">123</span> <script>var x = 1 < 2;</script>";

$bg = "#ffffff";
$df = "#000000";
$kw = "#0000ff";
$vb = "#008000";
$tn = "#2b91af";
$inf = "#2b91af";
$cs = "#800000";
$tx = "#000000";
$nm = "#098658";
$mn = "#795e26";
$cm = "#008000";
$fontName = "Arial";
$fontSize = 12;

$content = '<html><head><style type="text/css">body, pre { background: ' . $bg . '; color: ' . $df . "; font-family: '" . $fontName . "'; font-size: " . $fontSize . "pt; overflow: auto;}.kw { color: " . $kw . " }.vb { color: " . $vb . " }.tn { color: " . $tn . " }.in { color: " . $inf . " }.cs { color: " . $cs . " }.tx { color: " . $tx . " }.nm { color: " . $nm . " }.mn { color: " . $mn . " }.cm { color: " . $cm . " }</style></head><body scroll=\"no\"><pre id=\"translation\">" . $translation . '</pre></body></html>';

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
libxml_clear_errors();

$pre = $dom->getElementById('translation');
if ($pre) {
    $raw = $pre->textContent;
    $pre->nodeValue = $raw . "!";
    $pre->nodeValue = $raw;
}

$xpath = new DOMXPath($dom);
foreach ($xpath->query('//style') as $style) {
    $css = $style->textContent;
    $css = str_replace('font-size: ' . $fontSize . 'pt', 'font-size: 14pt', $css);
    $style->nodeValue = $css;
}

echo $dom->saveHTML();
?>
--EXPECT--
