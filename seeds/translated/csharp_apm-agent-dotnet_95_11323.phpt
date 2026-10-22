--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/95)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
fwrite($h, "a");
rewind($h);
stream_filter_append($h, 'convert.base64-encode', STREAM_FILTER_READ);
echo stream_get_contents($h), "\n";

$fp = fopen('php://temp', 'w+');
fwrite($fp, str_repeat("x", 3));
rewind($fp);
$meta = stream_get_meta_data($fp);
echo ($meta['seekable'] ? "1" : "0"), "\n";

$ary = [];
$ary["01"] = 1;
$ary[1] = 2;
echo count($ary), "\n";
?>
--EXPECT--
