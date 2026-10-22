--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/29)
--FILE--
<?php
$serverIp = getenv('serverIp') ?: '127.0.0.1';
$bootstrapUrl = getenv('bootstrapUrl') ?: 'https://127.0.0.1';

$ctx = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]]);
$s = @stream_socket_client("ssl://{$serverIp}:11207", $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $ctx);
if ($s !== false) {
    fwrite($s, "hello world!");
    fclose($s);
}

$u = parse_url($bootstrapUrl);
$host = $u['host'] ?? $bootstrapUrl;
$port = $u['port'] ?? (($u['scheme'] ?? 'https') === 'https' ? 443 : 80);
$path = ($u['path'] ?? '/') . (isset($u['query']) ? '?' . $u['query'] : '');
$ctx2 = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]]);
$r = @file_get_contents(($u['scheme'] ?? 'https') . "://{$host}:{$port}{$path}", false, $ctx2);
echo "done\n";
?>
--EXPECT--
