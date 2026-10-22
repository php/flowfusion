--TEST--
Translated seed (inferredbugs-csharp: Twitterizer/2)
--FILE--
<?php
$json = '{"ids":[1,2,3,9007199254740993],"next_cursor":9223372036854775807,"previous_cursor":-9223372036854775808}';
$v = json_decode($json, true);

if (!is_array($v) || !array_key_exists('ids', $v)) {
    exit;
}

$ids = json_decode(json_encode($v['ids']), true);
$result = [
    'NextCursor' => (int)$v['next_cursor'],
    'PreviousCursor' => (int)$v['previous_cursor'],
    'Annotations' => []
];

foreach ($ids as $id) {
    $result['ids'][] = $id;
}

if (($v2 = json_decode('{"ids":[],"next_cursor":0,"previous_cursor":0}', true)) && array_key_exists('ids', $v2)) {
    $tmp = json_decode(json_encode($v2['ids']), true);
    foreach ($tmp as $id) {
        $result['ids'][] = $id;
    }
}

echo count($result['ids']) . "\n";
?>
--EXPECT--
