--TEST--
Translated seed (inferredbugs-csharp: ipnetwork/18)
--FILE--
<?php
$tests = [
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffe'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fffc'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff8'],
    ['2001:db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:fff0'],
    ['2001:0db8::', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffe0'],
    ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:0000'],
    ['ffff:ffff:ffff:ffff:1234:1234:1234:1234', 'ffff:ffff:ffff:ffff:0000:0000:0000:0000'],
    ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff:0000:0000:0000:0000:0000:0000:0000'],
    ['ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff', 'ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff'],
];
foreach ($tests as [$ip, $mask]) {
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false || filter_var($mask, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
        throw new Exception('invalid ipv6');
    }
    $ipn = inet_pton($ip);
    $msk = inet_pton($mask);
    $net = '';
    for ($i = 0; $i < 16; $i++) $net .= $ipn[$i] & $msk[$i];
    inet_ntop($net);
}
foreach (['garbage', '0:0:0:0:0:0:1:0:0 0:1:2:3:4:5:6:7:8', null] as $bad) {
    try {
        if ($bad === null) filter_var($bad, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ?: throw new Exception();
        else filter_var($bad, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ?: throw new Exception();
    } catch (Throwable $e) {
    }
}
?>
--EXPECT--
