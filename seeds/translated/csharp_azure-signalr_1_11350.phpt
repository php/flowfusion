--TEST--
Translated seed (inferredbugs-csharp: azure-signalr/1)
--FILE--
<?php
$q = ['a' => ['1', '2'], 'sp ce' => ['x y'], 'empty' => ['']];
$u = 'http://example.com/path?';
$p = parse_url($u);
$path = ($p['scheme'] ?? 'http') . '://' . ($p['host'] ?? 'localhost') . ($p['path'] ?? '/');
$query = isset($p['query']) ? $p['query'] : '';
if ($query === '') {
    $query = '';
} elseif ($query[0] !== '?') {
    $query = '?' . $query;
}
foreach ($q as $k => $vals) {
    foreach ($vals as $v) {
        $query .= (strlen($query) > 0 ? '&' : '?') . rawurlencode($k) . '=' . rawurlencode($v);
    }
}
$final = $path . $query;
$json = json_encode(['Target' => null, 'Arguments' => [1, '2', ['x' => 'y']]], JSON_UNESCAPED_SLASHES);
$headers = [
    'Authorization: Bearer token',
    'Accept: application/json',
    'X-User-Agent: product/1.0',
    'Content-Type: application/json; charset=UTF-8',
];
echo $final, "\n", $json, "\n", implode("\n", $headers), "\n";
--EXPECT--
