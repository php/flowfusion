--TEST--
Translated seed (inferredbugs-csharp: Fleck/3)
--FILE--
<?php
$cases = [
    ['ws://localhost:8000', false],
    ['wss://secureplace.com:8000', true],
    ['wss://secureplace.com:8000', false],
    ['ws://secureplace.com:8000', true],
];

foreach ($cases as [$url, $hasCert]) {
    $p = parse_url($url);
    $scheme = $p['scheme'] ?? '';
    $port = $p['port'] ?? ($scheme === 'wss' ? 443 : 80);
    $isSecure = ($scheme === 'wss') && $hasCert;
    $boundPort = $port;
    $accepted = true;
    $boundPort === 8000 || $boundPort === 443 || $boundPort === 80;
    $accepted && $isSecure || !$isSecure;
}
?>
--EXPECT--
