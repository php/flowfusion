--TEST--
Translated seed (inferredbugs-csharp: sones/5)
--FILE--
<?php
$entries = [
    ['id' => '1', 'indexId' => 'idx', 'vertexId' => 9223372036854775807, 'propertyId' => null, 'text' => 'alpha beta'],
    ['id' => '2', 'indexId' => 'idx', 'vertexId' => -9223372036854775808, 'propertyId' => 0, 'text' => 'alpha gamma'],
    ['id' => '3', 'indexId' => 'idx', 'vertexId' => 0, 'propertyId' => 1, 'text' => 'delta'],
];

$docs = [];
foreach ($entries as $e) {
    $doc = $e;
    $docs[$e['id']] = $doc;
}

$query = 'alpha';
$innerQuery = 'gamma';

$matches = [];
foreach ($docs as $doc) {
    if (strpos($doc['text'], $query) !== false && strpos($doc['text'], $innerQuery) !== false) {
        $matches[] = $doc;
    }
}

$toDelete = [];
foreach ($docs as $doc) {
    if (strpos($doc['text'], 'alpha') !== false && ($doc['propertyId'] ?? null) === 0) {
        $toDelete[] = $doc['id'];
    }
}
foreach ($toDelete as $id) {
    unset($docs[$id]);
}

$has = false;
foreach ($docs as $doc) {
    if (strpos($doc['text'], 'delta') !== false) {
        $has = true;
        break;
    }
}

$values = [];
foreach ($docs as $doc) {
    $values[(string)$doc['vertexId']] = true;
}

echo count($matches) . "\n";
echo ($has ? '1' : '0') . "\n";
echo count($values) . "\n";
?>
--EXPECT--
