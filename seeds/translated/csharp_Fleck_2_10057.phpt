--TEST--
Translated seed (inferredbugs-csharp: Fleck/2)
--FILE--
<?php
$u = parse_url('wss://secureplace.com:8000');
$secure = ($u['scheme'] ?? '') === 'wss';
$cert = 'MyCert.cer';
$secure = $secure && $cert !== '';
$u = parse_url('wss://secureplace.com:8000');
$secure2 = ($u['scheme'] ?? '') === 'wss';
$u = parse_url('ws://secureplace.com:8000');
$secure3 = ($u['scheme'] ?? '') === 'wss' && $cert !== '';
echo ($secure ? '1' : '0') . ($secure2 ? '1' : '0') . ($secure3 ? '1' : '0');
?>
--EXPECT--
