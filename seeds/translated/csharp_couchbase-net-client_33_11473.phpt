--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/33)
--FILE--
<?php
$cases = [
    ['beer', 'brewery_beers', 'beer-sample', null, null],
    ['beer', 'view_that_does_not_exist', 'beer-sample', null, null],
    ['beer', 'brewery_beers', 'beer-sample', true, null],
    ['beer', 'brewery_beers', 'beer-sample', null, 'http://192.168.56.105:8092/'],
];

foreach ($cases as [$design, $view, $bucket, $group, $baseUri]) {
    $query = ['design' => $design, 'view' => $view, 'bucket' => $bucket];
    if ($group !== null) $query['group'] = $group;
    if ($baseUri !== null) $query['baseUri'] = $baseUri;

    $url = ($baseUri ?? 'http://127.0.0.1:8093/') . $bucket . '/_design/' . rawurlencode($design) . '/_view/' . rawurlencode($view);
    if ($group !== null) $url .= '?group=' . ($group ? 'true' : 'false');

    $ctx = stream_context_create(['http' => ['ignore_errors' => true, 'timeout' => 2]]);
    $body = @file_get_contents($url, false, $ctx);
    $status = 0;
    $message = '';
    if (isset($http_response_header[0]) && preg_match('~HTTP/\S+\s+(\d+)~', $http_response_header[0], $m)) $status = (int)$m[1];
    if ($body === false) {
        $e = error_get_last();
        $message = $e['message'] ?? '';
    } else {
        $message = $body;
    }

    if ($status === 200) {
        json_decode($body, true);
    } elseif ($status === 404 || $status === 400 || $status === 503) {
        strlen($message);
    }
}
?>
--EXPECT--
