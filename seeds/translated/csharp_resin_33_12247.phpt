--TEST--
Translated seed (inferredbugs-csharp: resin/33)
--FILE--
<?php
$documents = [
    ['___docid' => 1, 'a' => 'alpha beta', '__skip' => 'x'],
    ['___docid' => 2, 'a' => 'beta gamma', 'b' => 'delta'],
    ['___docid' => 3, 'a' => 'alpha alpha', 'b' => 'beta'],
];

$excludeKeyIds = [2];

$newColumns = [];
$docVectors = [];

foreach ($documents as $doc) {
    $docId = (int)$doc['___docid'];

    foreach ($doc as $key => $value) {
        $strKey = (string)$key;
        if (strncmp($strKey, '__', 2) === 0) {
            continue;
        }

        $keyId = crc32($strKey);
        if (in_array($keyId, $excludeKeyIds, true)) {
            continue;
        }

        $terms = preg_split('/\s+/', trim((string)$value), -1, PREG_SPLIT_NO_EMPTY);
        $vector = [];
        foreach ($terms as $term) {
            $id = crc32($term);
            $vector[$id] = ($vector[$id] ?? 0) + 1;
        }
        ksort($vector);

        if (!isset($newColumns[$keyId])) {
            $newColumns[$keyId] = [];
        }
        $newColumns[$keyId][$docId] = $vector;
        $docVectors[$docId][$keyId] = $vector;
    }
}

foreach ($newColumns as $keyId => $column) {
    ksort($column);
    $newColumns[$keyId] = $column;
}

echo "ok\n";
--EXPECT--
