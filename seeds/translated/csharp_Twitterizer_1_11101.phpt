--TEST--
Translated seed (inferredbugs-csharp: Twitterizer/1)
--FILE--
<?php
$json = '{"ids":[0,1,-1,9223372036854775807,-9223372036854775808,9007199254740993],"next_cursor":9223372036854775807,"previous_cursor":-9223372036854775808}';
$v = json_decode($json, true);
if (!is_array($v) || !isset($v['ids']) || !is_array($v['ids'])) {
    exit;
}
$r = [
    'next_cursor' => (int)$v['next_cursor'],
    'previous_cursor' => (int)$v['previous_cursor'],
    'ids' => [],
    'annotations' => []
];
foreach ($v['ids'] as $id) {
    $r['ids'][] = $id + 0;
}
$clone = $r;
$clone['next_cursor'] = $r['previous_cursor'];
$clone['previous_cursor'] = $r['next_cursor'];
$clone['ids'][] = $clone['next_cursor'];
$clone['ids'][] = $clone['previous_cursor'];
echo count($r['ids']) . "\n";
echo $r['next_cursor'] . "\n";
echo $r['previous_cursor'] . "\n";
echo json_encode($clone, JSON_PRESERVE_ZERO_FRACTION) . "\n";
--EXPECT--
