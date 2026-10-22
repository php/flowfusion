--TEST--
Translated seed (inferredbugs-csharp: dns/1)
--FILE--
<?php
$ip = "192.0.2.1";
$a = inet_pton($ip);
if (strlen($a) === 4) {
    $r = implode(".", array_reverse(array_map("strval", unpack("C*", $a)))) . ".in-addr.arpa";
} else {
    $n = [];
    for ($i = 0, $l = strlen($a); $i < $l; $i++) {
        $b = ord($a[$i]);
        $n[] = dechex(($b >> 4) & 15);
        $n[] = dechex($b & 15);
    }
    $r = implode(".", array_reverse($n)) . ".ip6.arpa";
}
echo $r, PHP_EOL;
?>
--EXPECT--
