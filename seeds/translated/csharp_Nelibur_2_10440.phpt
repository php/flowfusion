--TEST--
Translated seed (inferredbugs-csharp: Nelibur/2)
--FILE--
<?php
$qs = [];
$qs['type'] = 'stdClass';
$payload = ['a' => 1, 'b' => [2, 3], 's' => "x\0y"];
$qs['request'] = base64_encode(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
$raw = base64_decode($qs['request'], true);
$obj = json_decode($raw);
$type = $qs['type'];
echo $type, "\n";
echo json_encode($obj, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), "\n";
?>
--EXPECT--
