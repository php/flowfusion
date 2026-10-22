--TEST--
Translated seed (inferredbugs-csharp: VirusTotalNet/4)
--FILE--
<?php
$u = 'http://example.com';
$u = trim($u);
$l = strtolower($u);
if (strpos($l, 'http://') !== 0 && strpos($l, 'https://') !== 0) $u = 'http://' . $u;
$p = parse_url($u);
if ($p !== false) {
    $scheme = isset($p['scheme']) ? $p['scheme'] : 'http';
    $host = isset($p['host']) ? $p['host'] : '';
    $path = isset($p['path']) ? $p['path'] : '';
    $base = $scheme . '://' . $host . $path;
    $useTls = true;
    $oldUrl = $base;
    if (trim($oldUrl) !== '') {
        if (stripos($oldUrl, 'https://') === 0) $oldUrl = substr($oldUrl, 8);
        elseif (stripos($oldUrl, 'http://') === 0) $oldUrl = substr($oldUrl, 7);
        $base = ($useTls ? 'https://' : 'http://') . $oldUrl;
    }
    $hashes = [];
    for ($i = 0; $i < 26; $i++) $hashes[] = str_repeat(dechex($i % 16), 64);
    $hashes = array_slice($hashes, 0, 25);
    $resource = implode(',', $hashes);
    $url = $base . '/file/' . substr(hash('sha256', $resource), 0, 64) . '/analysis/';
    $data = ['apikey' => str_repeat('a', 64), 'resource' => $resource, 'url' => $url, 'scan' => '1', 'comment' => 'ok'];
    $q = http_build_query($data);
    $h = hash('sha256', $q);
    echo $h, PHP_EOL;
}
?>
--EXPECT--
