--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/15)
--FILE--
<?php
$rows = [];
for ($i = 0; $i < 1000; $i++) {
    $rows[] = ["A" => $i, "B" => str_repeat("x", $i % 7)];
}
$tmp = tempnam(sys_get_temp_dir(), 'php');
$fp = fopen($tmp, 'w+');
fwrite($fp, json_encode($rows, JSON_UNESCAPED_UNICODE));
rewind($fp);
$data = json_decode(stream_get_contents($fp), true);
$out = [];
foreach ($data as $r) {
    $out[] = [$r["A"] + 1, substr($r["B"], 0, 3)];
}
fclose($fp);
unlink($tmp);
echo count($out), "\n";
?>
--EXPECT--
