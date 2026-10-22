--TEST--
Translated seed (inferredbugs-csharp: core/4)
--FILE--
<?php
$xml = '<changeset id="10" user="fred" uid="123" created_at="2008-11-08T19:07:39Z" open="true" min_lon="7.019182" min_lat="49.27854" max_lon="7.019749" max_lat="49.27931"><tag k="created_by" v="JOSM 1.61" /><tag k="comment" v="Just adding some streetnames" /></changeset>';
$dom = new DOMDocument();
$dom->loadXML($xml, LIBXML_NOBLANKS);
$out = $dom->saveXML($dom->documentElement);
$dom2 = new DOMDocument();
$dom2->loadXML($out, LIBXML_NOBLANKS);
$root = $dom2->documentElement;
$root->setAttribute('id', (string)(int)$root->getAttribute('id'));
$root->setAttribute('uid', (string)(int)$root->getAttribute('uid'));
$root->setAttribute('created_at', gmdate('Y-m-d\TH:i:s\Z', strtotime($root->getAttribute('created_at'))));
$root->setAttribute('min_lon', rtrim(rtrim(sprintf('%.6f', (float)$root->getAttribute('min_lon')), '0'), '.'));
$root->setAttribute('min_lat', rtrim(rtrim(sprintf('%.5f', (float)$root->getAttribute('min_lat')), '0'), '.'));
$root->setAttribute('max_lon', rtrim(rtrim(sprintf('%.6f', (float)$root->getAttribute('max_lon')), '0'), '.'));
$root->setAttribute('max_lat', rtrim(rtrim(sprintf('%.5f', (float)$root->getAttribute('max_lat')), '0'), '.'));
echo $dom2->saveXML($root);
?>
--EXPECT--
