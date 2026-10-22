--TEST--
Translated seed (inferredbugs-csharp: XamlStyler/1)
--FILE--
<?php
$source = '<root><Grid><B Grid.Row="2" Grid.Column="1"/><!--x--><A Grid.Row="1" Grid.Column="2"/><C Grid.Row="1" Grid.Column="1"/></Grid></root>';
$doc = new DOMDocument();
$doc->preserveWhiteSpace = true;
$doc->loadXML($source, LIBXML_NOBLANKS);
$grid = $doc->getElementsByTagName('Grid')->item(0);
$nodes = [];
foreach (iterator_to_array($grid->childNodes) as $child) {
    if ($child->nodeType === XML_ELEMENT_NODE) {
        $row = 0;
        $col = 0;
        if ($child->attributes->getNamedItem('Grid.Row')) $row = (int)$child->attributes->getNamedItem('Grid.Row')->nodeValue;
        if ($child->attributes->getNamedItem('Grid.Column')) $col = (int)$child->attributes->getNamedItem('Grid.Column')->nodeValue;
        $nodes[] = [$child, $row, $col];
    } else {
        $prev = $nodes ? $nodes[0] : null;
        $nodes[] = [$child, $prev ? $prev[1] : 0, $prev ? $prev[2] : 0];
    }
}
usort($nodes, fn($a, $b) => $a[1] <=> $b[1] ?: $a[2] <=> $b[2]);
while ($grid->firstChild) $grid->removeChild($grid->firstChild);
foreach ($nodes as $n) $grid->appendChild($n[0]);
echo $doc->saveXML();
?>
--EXPECT--
