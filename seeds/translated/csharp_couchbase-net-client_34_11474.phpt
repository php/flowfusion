--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/34)
--FILE--
<?php
$cases = [
    ['beer', 'brewery_beers', 'beer-sample', null, null],
    ['beer', 'view_that_does_not_exist', 'beer-sample', null, null],
    ['beer', 'brewery_beers', 'beer-sample', true, null],
    ['beer', 'brewery_beers', 'beer-sample', null, 'http://192.168.56.105:8092/'],
];

foreach ($cases as [$design, $view, $bucket, $group, $baseUri]) {
    $q = http_build_query(array_filter([
        'stale' => 'false',
        'reduce' => 'false',
        'group' => $group,
    ], static fn($v) => $v !== null), '', '&', PHP_QUERY_RFC3986);

    $url = ($baseUri ?? 'http://127.0.0.1:8092/') . $bucket . '/_design/' . rawurlencode($design) . '/_view/' . rawurlencode($view) . ($q !== '' ? '?' . $q : '');
    $ctx = stream_context_create(['http' => ['ignore_errors' => true, 'timeout' => 2]]);
    $body = @file_get_contents($url, false, $ctx);

    if (isset($http_response_header[0]) && preg_match('~\s(\d{3})\s~', $http_response_header[0], $m)) {
        $code = (int)$m[1];
    } else {
        $code = 503;
    }

    json_decode($body ?? '', true);
    $err = json_last_error() === JSON_ERROR_NONE ? null : json_last_error_msg();

    if ($code === 200) {
        $data = json_decode($body, true);
        $rows = $data['rows'] ?? null;
        if ($rows === null) {
            $rows = [];
        }
    } elseif ($code === 404) {
        $msg = $err ?? 'not found';
        $success = false;
    } elseif ($code === 400) {
        $msg = $err ?? 'query_parse_error';
        $success = false;
    } else {
        $success = false;
        $rows = [];
    }
}

echo "done\n";
?>
--EXPECT--
