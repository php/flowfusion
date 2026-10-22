--TEST--
Translated seed (inferredbugs-csharp: core/2)
--FILE--
<?php
$xmls = [
    '<diffResult version="0.6"></diffResult>',
    '<diffResult generator="OsmSharp" version="0.6"></diffResult>',
    '<diffResult generator="OsmSharp" version="0.6"><node old_id="1" new_id="2" new_version="2" /></diffResult>',
];

foreach ($xmls as $xml) {
    $s = simplexml_load_string($xml);
    if ($s === false) {
        continue;
    }
    $version = (string)$s['version'];
    $generator = isset($s['generator']) ? (string)$s['generator'] : null;
    $results = [];
    foreach ($s->children() as $child) {
        $results[] = [
            'name' => $child->getName(),
            'old_id' => (string)$child['old_id'],
            'new_id' => (string)$child['new_id'],
            'new_version' => (string)$child['new_version'],
        ];
    }
    $out = '<diffResult';
    if ($generator !== null && $generator !== '') $out .= ' generator="' . htmlspecialchars($generator, ENT_QUOTES | ENT_XML1) . '"';
    if ($version !== '') $out .= ' version="' . htmlspecialchars($version, ENT_QUOTES | ENT_XML1) . '"';
    $out .= '>';
    foreach ($results as $r) {
        $out .= '<' . $r['name'];
        if ($r['old_id'] !== '') $out .= ' old_id="' . htmlspecialchars($r['old_id'], ENT_QUOTES | ENT_XML1) . '"';
        if ($r['new_id'] !== '') $out .= ' new_id="' . htmlspecialchars($r['new_id'], ENT_QUOTES | ENT_XML1) . '"';
        if ($r['new_version'] !== '') $out .= ' new_version="' . htmlspecialchars($r['new_version'], ENT_QUOTES | ENT_XML1) . '"';
        $out .= ' />';
    }
    $out .= '</diffResult>';
}
?>
--EXPECT--
