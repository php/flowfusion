--TEST--
Translated seed (inferredbugs-csharp: netDxf/3)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
fwrite($h, "0000000000000000000000");
rewind($h);
$s = fread($h, 22);
$k = substr($s, 0, 18) === "AutoCAD Binary DXF";
$a = [];
$a["x"] = $a["x"] ?? [];
$a["x"][] = $k;
$u = "\\U+0041\\U+0042\\U+0043";
$r = preg_replace_callback('/\\\\U\\+([0-9A-Fa-f]{4})/', fn($m) => html_entity_decode('&#'.hexdec($m[1]).';', ENT_NOQUOTES, 'UTF-8'), $u);
echo $r;
?>
--EXPECT--
