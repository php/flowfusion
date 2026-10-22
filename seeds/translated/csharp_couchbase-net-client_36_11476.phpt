--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/36)
--FILE--
<?php
$queries = [
    ['beer', 'all_beers', 'beer-sample', null],
    ['beer', 'brewery_beers', 'beer-sample', null],
    ['beer', 'view_that_does_not_exist', 'beer-sample', null],
    ['beer', 'brewery_beers', 'beer-sample', ['group' => true]],
    ['beer', 'brewery_beers', 'beer-sample', ['baseUri' => 'http://192.168.56.105:8092/']],
    ['beer', 'brewery_beers', 'beer-sample', ['baseUri' => 'http://192.168.62.200:8092/']],
    ['docs', 'all_docs', 'default', null],
];

foreach ($queries as $q) {
    [$ddoc, $view, $bucket, $opts] = $q;
    $url = 'http://127.0.0.1/' . rawurlencode($bucket) . '/' . rawurlencode($ddoc) . '/' . rawurlencode($view);
    if (is_array($opts)) {
        if (!empty($opts['group'])) $url .= '?group=true';
        if (!empty($opts['baseUri'])) $url = $opts['baseUri'] . ltrim($url, '/');
    }
    $ctx = stream_context_create(['http' => ['timeout' => 1, 'ignore_errors' => true]]);
    $body = @file_get_contents($url, false, $ctx);
    $status = 0;
    if (!empty($http_response_header[0]) && preg_match('~\s(\d{3})\s~', $http_response_header[0], $m)) $status = (int)$m[1];
    echo $ddoc . ' ' . $view . ' ' . $bucket . ' ' . $status . ' ' . ($body === false ? '' : substr($body, 0, 32)) . PHP_EOL;
}

$n = 10000;
for ($i = 0; $i < $n; $i++) {
    $ctx = stream_context_create(['http' => ['timeout' => 1, 'ignore_errors' => true]]);
    $r = @file_get_contents('http://127.0.0.1/default/docs/all_docs', false, $ctx);
    echo $i . ' ' . ($r !== false ? '1' : '0') . PHP_EOL;
}
?>
--EXPECT--
