--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/26)
--FILE--
<?php
date_default_timezone_set('UTC');

$source = "A中文B";
$hex = bin2hex($source);
$roundtrip = hex2bin($hex);
$u = preg_replace_callback('/[^\x00-\x7f]/u', function ($m) {
    $c = mb_convert_encoding($m[0], 'UTF-16LE', 'UTF-8');
    return sprintf('\\u%02x%02x', ord($c[1]), ord($c[0]));
}, $source);

$bytes = [0x78, 0x56, 0x34, 0x12, 0xff];
$int32 = unpack('l', pack('C4', $bytes[0], $bytes[1], $bytes[2], $bytes[3]))[1];

$ts = 0;
$dt = (new DateTimeImmutable('@' . $ts))->setTimezone(new DateTimeZone('America/Los_Angeles'));
$ts2 = $dt->setTimezone(new DateTimeZone('UTC'))->getTimestamp();

$page = intdiv(10 - 1, 3) + 1;

echo $roundtrip, "\n", $u, "\n", $int32, "\n", $dt->format('Y-m-d H:i:s'), "\n", $ts2, "\n", $page, "\n";
?>
--EXPECT--
