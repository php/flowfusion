--TEST--
Translated seed (inferredbugs-csharp: azure-signalr/4)
--FILE--
<?php
$u = 'http://example.com/path?';
$q = ['a' => ['1', '2'], 'b' => ['x y'], 'c' => ['%20', '']];
$p = parse_url($u);
$scheme = $p['scheme'] ?? 'http';
$host = $p['host'] ?? '';
$port = isset($p['port']) ? ':' . $p['port'] : '';
$path = $p['path'] ?? '';
$query = isset($p['query']) ? $p['query'] : '';
if ($query === '') {
    $query = '';
} elseif ($query[0] !== '?') {
    $query = '?' . $query;
}
foreach ($q as $k => $vals) {
    foreach ($vals as $v) {
        $query .= ($query === '' ? '?' : '&') . rawurlencode($k) . '=' . rawurlencode($v);
    }
}
$uri = $scheme . '://' . $host . $port . $path . $query;
$payload = ['Target' => 'm', 'Arguments' => [null, 1, '']];
$json = json_encode($payload, JSON_UNESCAPED_SLASHES);
if ($json === false) {
    $json = '';
}
echo $uri, "\n", $json, "\n";
?>
--EXPECT--
