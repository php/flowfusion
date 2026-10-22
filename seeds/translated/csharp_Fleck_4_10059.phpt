--TEST--
Translated seed (inferredbugs-csharp: Fleck/4)
--FILE--
<?php
$u = parse_url('wss://secureplace.com:8000');
$secure = (($u['scheme'] ?? '') === 'wss') && true;
$secure = $secure && !empty($u['host']) && !empty($u['port']);
if (!$secure) {
    exit(1);
}

$u = parse_url('wss://secureplace.com:8000');
$secure = (($u['scheme'] ?? '') === 'wss') && false;
if ($secure) {
    exit(1);
}

$u = parse_url('ws://secureplace.com:8000');
$secure = (($u['scheme'] ?? '') === 'wss') && true;
if ($secure) {
    exit(1);
}

$u = parse_url('ws://localhost:8000');
$port = (int)($u['port'] ?? 0);
if ($port !== 8000) {
    exit(1);
}
echo "";
?>
--EXPECT--
