--TEST--
Translated seed (inferredbugs-csharp: sones/4)
--FILE--
<?php
$docs = [];
$add = function($id, $indexId, $vertexId, $propertyId, $text) use (&$docs) {
    $doc = ['id' => $id, 'indexId' => $indexId, 'vertexId' => $vertexId, 'text' => $text];
    if ($propertyId !== null) $doc['propertyId'] = (int)$propertyId;
    $docs[] = $doc;
};
$match = function($doc, $query) {
    $terms = preg_split('/\s+/', trim($query), -1, PREG_SPLIT_NO_EMPTY);
    foreach ($terms as $t) {
        if (strpos($doc['text'], $t) === false && strpos($doc['id'], $t) === false && strpos($doc['indexId'], $t) === false) return false;
    }
    return true;
};
$get = function($max, $query, $inner = null) use (&$docs, $match) {
    $out = [];
    foreach ($docs as $d) {
        if ($match($d, $query) && ($inner === null || $match($d, $inner))) {
            $out[] = $d;
            if (count($out) >= $max) break;
        }
    }
    return $out;
};
$delete = function($query, $select = null) use (&$docs, $match) {
    $keep = [];
    foreach ($docs as $d) {
        $rm = $match($d, $query);
        if ($rm && $select !== null && !$select($d)) $rm = false;
        if (!$rm) $keep[] = $d;
    }
    $docs = $keep;
};
$add('a1', 'idx', 1, null, 'alpha beta');
$add('a2', 'idx', 2, 7, 'beta gamma');
$add('a3', 'idx', 3, null, 'alpha delta');
$delete('beta', function($e) { return ($e['vertexId'] % 2) === 0; });
$r = $get(10, 'alpha', 'delta');
if (!$r) $r = $get(1, 'gamma');
echo count($docs), "\n", count($r), "\n";
?>
--EXPECT--
