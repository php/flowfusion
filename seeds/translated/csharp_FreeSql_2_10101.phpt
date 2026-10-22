--TEST--
Translated seed (inferredbugs-csharp: FreeSql/2)
--FILE--
<?php
$size = 500;
$rows = [];
for ($i = 0; $i < $size; $i++) {
    $rows[] = [
        'id' => $i + 1,
        'create_time' => date('Y-m-d H:i:s'),
        'is_deleted' => false,
        'title' => "Insert_$i",
        'url' => "Url_$i",
    ];
}

$store = [];
foreach ($rows as $r) {
    $store[] = $r;
}
foreach ($store as $k => $r) {
    $store[$k]['title'] = $r['title'];
    $store[$k]['url'] = $r['url'];
}
$out = [];
for ($i = 0; $i < 1; $i++) {
    $out[] = array_slice($store, 0, $size);
    $out[] = array_slice($store, 0, 1);
}
echo count($out) . PHP_EOL;
?>
--EXPECT--
