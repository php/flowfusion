--TEST--
Translated seed (inferredbugs-csharp: invest-openapi-csharp-sdk/1)
--FILE--
<?php
$baseUri = 'https://api-invest.tinkoff.ru/openapi/';
$sandboxBaseUri = 'https://api-invest.tinkoff.ru/openapi/sandbox/';
$webSocketBaseUri = 'wss://api-invest.tinkoff.ru/openapi/md/v1/md-openapi/ws';

$token = str_repeat('a', 1);

$connections = [
    [$baseUri, $webSocketBaseUri, $token],
    [$sandboxBaseUri, $webSocketBaseUri, $token],
];

foreach ($connections as $c) {
    [$httpBase, $wsBase, $t] = $c;
    $u = parse_url($httpBase);
    $v = parse_url($wsBase);
    $s = $u['scheme'] . '://' . $u['host'] . (isset($u['path']) ? $u['path'] : '') . '|' . $v['scheme'] . '://' . $v['host'] . (isset($v['path']) ? $v['path'] : '') . '|' . $t;
    strlen($s);
}
?>
--EXPECT--
