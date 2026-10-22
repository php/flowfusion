--TEST--
Translated seed (inferredbugs-csharp: Utf8Json/1)
--FILE--
<?php
$json = '{"Id":false}';
$v = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
if (isset($v['Id']) && is_bool($v['Id'])) {
    $out = json_encode(['Id' => (float)$v['Id']], JSON_THROW_ON_ERROR);
    $v2 = json_decode($out, true, 512, JSON_THROW_ON_ERROR);
    json_encode(['Id' => (float)$v2['Id']], JSON_THROW_ON_ERROR);
}
?>
--EXPECT--
