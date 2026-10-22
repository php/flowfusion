--TEST--
Translated seed (inferredbugs-csharp: Native.Framework/3)
--FILE--
<?php
$cases = [
    ["[CQ:at,qq=all] ", "[CQ:at,qq=123]"],
    ["[CQ:emoji,id=128]", "[CQ:face,id=14]"],
    ["a&b[c],", false],
    ["&#91;&#93;&#44;&amp;", true],
    ["[CQ:share,url=http://a/b?x=1&y=2,title=t,c]", false],
    ["[CQ:music,id=1,type=qqstyle=1]", false],
    ["[CQ:location,lat=1.5,lon=-2.25,zoom=15,title=x,content=y]", false],
];

foreach ($cases as $c) {
    $s = $c[0];
    $comma = $c[1];
    $t = str_replace("&", "&amp;", $s);
    $t = str_replace("[", "&#91;", $t);
    $t = str_replace("]", "&#93;", $t);
    if ($comma) $t = str_replace(",", "&#44;", $t);
    $u = str_replace("&#91;", "[", $t);
    $u = str_replace("&#93;", "]", $u);
    $u = str_replace("&#44;", ",", $u);
    $u = str_replace("&amp;", "&", $u);
    $b = base64_encode(pack("P", 1234567890123) . pack("V", 0x11223344) . pack("V", 0));
    $raw = base64_decode($b);
    $x = unpack("Pq/Va/Vb", $raw);
    $f = pack("P", 42) . pack("P", 7) . pack("V", 3) . "abc" . pack("V", 4) . "defg" . pack("V", 1) . pack("V", 20) . pack("V", 30) . pack("V", 2) . pack("V", 0) . pack("V", 1) . pack("V", 5) . pack("V", 0) . pack("V", 1);
    $g = base64_encode($f);
    $h = base64_decode($g);
    $y = unpack("Pgid/Pqq/Vn", $h);
}
echo "";
?>
--EXPECT--
