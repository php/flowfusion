--TEST--
Translated seed (inferredbugs-csharp: WebDavClient/1)
--FILE--
<?php
$h = [];
$h["a"] = "1";
$h["a"] = "2";
$h["b"] = "3";
$h["a"] = "4";
$h["c"] = "5";
$h["b"] = "6";
$h["d"] = "7";
$h["c"] = "8";

$s = "";
foreach ($h as $k => $v) {
    $s .= $k . "=" . $v . ";";
}

$u = "http://example.com/base/";
$r1 = parse_url($u . "../x?y=1#z");
$r2 = parse_url("https://example.com:443/a/b/../c");
$r3 = parse_url("/relative/path");

$x = [];
$x["k"] = ["v" => 1];
$y = $x;
$y["k"]["v"] = 2;
$z = $x["k"]["v"];

$a = str_repeat("A", 1024);
$b = substr($a, 1) . substr($a, 0, 1);
$c = rawurldecode(rawurlencode($b));

$enc = mb_detect_encoding($c, ["UTF-8", "ISO-8859-1", "ASCII"], true);
if ($enc === false) {
    $enc = "UTF-8";
}
$d = mb_convert_encoding($c, "UTF-8", $enc);

echo strlen($s) + strlen($d) + count($r1) + count($r2) + (is_array($r3) ? count($r3) : 0) + $z;
?>
--EXPECT--
