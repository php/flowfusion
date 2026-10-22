--TEST--
Translated seed (inferredbugs-csharp: Open-Xml-PowerTools/1)
--FILE--
<?php
$xml = <<<XML
<root>
  <num numId="1">
    <abstractNumId val="10"/>
    <lvlOverride ilvl="0"><startOverride val="3"/><lvl><start val="3"/><lvlText val="%1."/></lvl></lvlOverride>
    <abstractNum abstractNumId="10">
      <multiLevelType val="hybridMultilevel"/>
      <lvl ilvl="0"><start val="1"/><lvlText val="%1."/><numFmt val="decimal"/></lvl>
      <lvl ilvl="1"><start val="1"/><lvlText val="%1.%2."/><numFmt val="decimal"/></lvl>
    </abstractNum>
  </num>
  <style styleId="S1" type="paragraph" default="1">
    <pPr><numPr><numId val="1"/><ilvl val="1"/></numPr></pPr>
  </style>
</root>
XML;

$doc = new DOMDocument();
$doc->loadXML($xml);
$xp = new DOMXPath($doc);

$cache = [];
$paras = [];
for ($i = 0; $i < 3; $i++) {
    $p = $doc->createElement('p');
    $p->setAttribute('style', 'S1');
    $p->setAttribute('numId', '1');
    $p->setAttribute('ilvl', (string)($i % 2));
    $paras[] = $p;
}

foreach ($paras as $p) {
    $style = $p->getAttribute('style');
    $numId = $p->getAttribute('numId');
    $ilvl = (int)$p->getAttribute('ilvl');
    $key = $style . '|' . $numId;
    if (!isset($cache[$key])) {
        $cache[$key] = ['start' => [0 => 3, 1 => 1], 'text' => [0 => '%1.', 1 => '%1.%2.']];
    }
    $nums = [];
    for ($lvl = 0; $lvl <= $ilvl; $lvl++) {
        $nums[$lvl] = $cache[$key]['start'][$lvl] + $lvl + $ilvl;
    }
    $text = $cache[$key]['text'][$ilvl];
    $out = '';
    for ($i = 0; $i < strlen($text); $i++) {
        if ($text[$i] === '%' && $i + 1 < strlen($text) && ctype_digit($text[$i + 1])) {
            $idx = (int)$text[$i + 1] - 1;
            $out .= (string)($nums[$idx] ?? 0);
            $i++;
        } else {
            $out .= $text[$i];
        }
    }
    echo $out, "\n";
}
?>
--EXPECT--
