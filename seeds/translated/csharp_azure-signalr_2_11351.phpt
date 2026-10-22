--TEST--
Translated seed (inferredbugs-csharp: azure-signalr/2)
--FILE--
<?php
$cs = "endpoint=https://example.com/;version=1.2-alpha;port=65535;clientEndpoint=http://client.example.com/;accesskey=abc";
$p = array_filter(array_map('trim', explode(';', $cs)), 'strlen');
if (count($p) < 2) exit;
$d = [];
foreach ($p as $x) {
    $kv = explode('=', $x, 2);
    if (count($kv) !== 2) continue;
    $k = trim($kv[0]);
    if (array_key_exists(strtolower($k), $d)) exit;
    $d[strtolower($k)] = trim($kv[1]);
}
if (!isset($d['endpoint'])) exit;
$endpoint = rtrim($d['endpoint'], '/');
if (!filter_var($endpoint, FILTER_VALIDATE_URL) || !in_array(strtolower(parse_url($endpoint, PHP_URL_SCHEME)), ['http', 'https'], true)) exit;
if (isset($d['version']) && !preg_match('/^1\.\d+(?:[\w\-.]+)?$/', $d['version'])) exit;
if (isset($d['port']) && (!ctype_digit($d['port']) || (int)$d['port'] <= 0 || (int)$d['port'] > 65535)) exit;
if (isset($d['clientendpoint'])) {
    $u = $d['clientendpoint'];
    if (!filter_var($u, FILTER_VALIDATE_URL) || !in_array(strtolower(parse_url($u, PHP_URL_SCHEME)), ['http', 'https'], true)) exit;
}
$type = isset($d['authtype']) ? strtolower($d['authtype']) : '';
if ($type === 'aad') {
    $x = $d['clientid'] ?? '';
    $y = $d['tenantid'] ?? '';
    if ($x !== '' && $y !== '') {
        if (isset($d['clientsecret'])) {
            $z = $d['clientsecret'];
        } elseif (isset($d['clientcert']) && is_file($d['clientcert'])) {
            $z = file_get_contents($d['clientcert']);
        }
    }
} else {
    $k = $d['accesskey'] ?? '';
}
echo "ok";
?>
--EXPECT--
