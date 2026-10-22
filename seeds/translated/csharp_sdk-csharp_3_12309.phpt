--TEST--
Translated seed (inferredbugs-csharp: sdk-csharp/3)
--FILE--
<?php
$e = [
    'specversion' => '1.0',
    'type' => 'event-type',
    'id' => 'event-id',
    'source' => '//event-source',
    'text' => "GBP=\u{00A3}",
    'data' => ['value' => 100],
    'data_base64' => base64_encode("\x00\x01\x02\x03"),
    'datacontenttype' => 'text/plain; charset=utf-8',
];
$j = json_encode($e, JSON_UNESCAPED_UNICODE);
$a = json_decode($j, true);
$b = json_encode([$a, $a], JSON_UNESCAPED_UNICODE);
$c = json_decode($b, true);
$d = json_encode(['specversion' => '1.0', 'type' => 'test-type', 'id' => 'test-id', 'source' => 'https://event-source', 'text' => "GBP=\u{00A3}"], JSON_UNESCAPED_UNICODE);
$e2 = json_decode($d, true);
echo $e2['type'], "\n", $c[0]['id'], "\n";
?>
--EXPECT--
