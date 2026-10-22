--TEST--
Translated seed (inferredbugs-csharp: Native.Framework/1)
--FILE--
<?php
$parts = [
    'all' => '[CQ:at,qq=all] ',
    'one' => '[CQ:at,qq=12345]',
    'emoji' => '[CQ:emoji,id=123]',
    'trope' => str_replace(['&', '[', ']', ','], ['&amp;', '&#91;', '&#93;', '&#44;'], 'a&b[c],d'),
    'untrope' => str_replace(['&#91;', '&#93;', '&#44;', '&amp;'], ['[', ']', ',', '&'], '&#91;a&#93;&#44;&amp;'),
    'share' => '[CQ:share' . ',url=' . str_replace(['&', '[', ']', ','], ['&amp;', '&#91;', '&#93;', '&#44;'], 'https://a/b?x=1&y=2') . ',title=' . str_replace(['&', '[', ']', ','], ['&amp;', '&#91;', '&#93;', '&#44;'], 't,1') . ']',
    'music' => '[CQ:music,id=7,type=' . str_replace(['&', '[', ']', ','], ['&amp;', '&#91;', '&#93;', '&#44;'], 'qq') . 'style=1]',
    'loc' => '[CQ:location,lat=' . 1.5 . ',lon=' . 2.5 . ',zoom=' . 15 . ',title=' . str_replace(['&', '[', ']', ','], ['&amp;', '&#91;', '&#93;', '&#44;'], 's') . ',content=' . str_replace(['&', '[', ']', ','], ['&amp;', '&#91;', '&#93;', '&#44;'], 'd') . ']',
    'anon' => '[CQ:anonymous,ignore=true]',
];
foreach ($parts as $v) {
    strlen($v);
}
$data = pack('P', 1234567890123) . pack('V', 4) . 'nick' . pack('V', 1) . pack('V', 18);
$b64 = base64_encode($data);
$raw = base64_decode($b64, true);
$off = 0;
$u64 = unpack('P', substr($raw, $off, 8))[1]; $off += 8;
$len = unpack('V', substr($raw, $off, 4))[1]; $off += 4;
$nick = substr($raw, $off, $len); $off += $len;
$sex = unpack('V', substr($raw, $off, 4))[1]; $off += 4;
$age = unpack('V', substr($raw, $off, 4))[1];
echo $u64 . "\n" . $nick . "\n" . $sex . "\n" . $age . "\n";
?>
--EXPECT--
