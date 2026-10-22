--TEST--
Translated seed (inferredbugs-csharp: Fleck/1)
--FILE--
<?php
$u = parse_url('wss://secureplace.com:8000');
$secure = (($u['scheme'] ?? '') === 'wss') && !empty('MyCert.cer');
if (!$secure) exit(1);

$u = parse_url('wss://secureplace.com:8000');
$secure = (($u['scheme'] ?? '') === 'wss') && !empty(null);
if ($secure) exit(1);

$u = parse_url('ws://secureplace.com:8000');
$secure = (($u['scheme'] ?? '') === 'wss') && !empty('MyCert.cer');
if ($secure) exit(1);

$port = (int)(parse_url('ws://localhost:8000', PHP_URL_PORT) ?: 0);
if ($port !== 8000) exit(1);
?>
--EXPECT--
