--TEST--
Translated seed (inferredbugs-java: clue/1)
--FILE--
<?php

$field = isset($argv[1]) ? $argv[1] : null;
$termVal = null;

if ($field) {
    $parts = explode(':', $field);
    if (count($parts) > 1) {
        $field = $parts[0];
        $termVal = $parts[1];
    }
}

$termCountMap = [];
$termMap = [];

$leaves = []; // This should be replaced with actual logic to get leaves from an index reader
foreach ($leaves as $leaf) {
    $terms = null; // Replace with logic to get terms from the leaf's reader
    if ($terms === null) {
        continue;
    }

    $te = $terms->iterator(); 
    if ($termVal !== null) {
        $te->seekCeil(new BytesRef($termVal));
        $termBytes = $te->term();
    } else {
        $termBytes = $te->next();
    }

    while (true) {
        if ($termBytes === null) break;
        if (!isset($termCountMap[$termBytes])) {
            $termCountMap[$termBytes] = 0;
            $termMap[$termBytes] = $te;
            break;
        }
        $termCountMap[$termBytes] += $te->docFreq();
        $termBytes = $te->next();
    }
}

while (!empty($termMap)) {
    $key = key($termMap);
    $count = $termCountMap[$key];
    unset($termCountMap[$key]);
    echo $key->utf8ToString() . " (" . $count . ") \n";
    $te = array_shift($termMap);
    $nextKey = $te->next();
    while (true) {
        if ($nextKey === null) break;
        if (!isset($termCountMap[$nextKey])) {
            $termCountMap[$nextKey] = 0;
            $termMap[$nextKey] = $te;
            break;
        }
        $termCountMap[$nextKey] += $te->docFreq();
        $nextKey = $te->next();
    }
}

?>
--EXPECT--
