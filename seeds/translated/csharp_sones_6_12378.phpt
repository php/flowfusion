--TEST--
Translated seed (inferredbugs-csharp: sones/6)
--FILE--
<?php
$docs = [];
function addEntry(&$docs, $entry) {
    $doc = [
        'id' => (string)$entry['id'],
        'indexId' => (string)$entry['indexId'],
        'vertexId' => (string)$entry['vertexId'],
        'text' => (string)$entry['text'],
    ];
    if (array_key_exists('propertyId', $entry) && $entry['propertyId'] !== null) $doc['propertyId'] = (string)$entry['propertyId'];
    $docs[] = $doc;
}
function matchQuery($doc, $q) {
    $q = trim($q);
    if ($q === '') return false;
    if ($q === '*:*') return true;
    if (preg_match('/^(\w+):"(.*)"$/', $q, $m)) return isset($doc[$m[1]]) && $doc[$m[1]] === stripcslashes($m[2]);
    if (preg_match('/^(\w+):([^\s]+)$/', $q, $m)) return isset($doc[$m[1]]) && $doc[$m[1]] === $m[2];
    return isset($doc['text']) && stripos($doc['text'], $q) !== false;
}
function getEntries($docs, $max, $query, $inner = null) {
    $out = [];
    foreach ($docs as $d) {
        if (matchQuery($d, $query) && ($inner === null || matchQuery($d, $inner))) {
            $out[] = $d;
            if (count($out) >= $max) break;
        }
    }
    return $out;
}
addEntry($docs, ['id' => '1', 'indexId' => 'A', 'vertexId' => PHP_INT_MAX, 'propertyId' => null, 'text' => 'alpha beta']);
addEntry($docs, ['id' => '2', 'indexId' => 'A', 'vertexId' => PHP_INT_MAX - 1, 'propertyId' => 0, 'text' => 'alpha']);
addEntry($docs, ['id' => '3', 'indexId' => 'B', 'vertexId' => -1, 'propertyId' => 7, 'text' => 'gamma']);
$hits = getEntries($docs, 1, 'alpha');
if (count($hits) > 0) $hits = getEntries($docs, count($docs), 'alpha', 'indexId:A');
$docs = array_values(array_filter($docs, fn($d) => !matchQuery($d, 'id:2')));
$docs = array_values(array_filter($docs, fn($d) => !matchQuery($d, 'text:gamma')));
foreach ($docs as $d) {
    $x = $d['vertexId'];
    $y = (int)$d['vertexId'];
    $z = $d['propertyId'] ?? null;
}
?>
--EXPECT--
