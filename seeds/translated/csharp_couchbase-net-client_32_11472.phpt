--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/32)
--FILE--
<?php
$u = parse_url('http://127.0.0.1:1/');
$h = $u['host'] ?? '';
$p = $u['port'] ?? 80;
$fp = @fsockopen($h, $p, $errno, $errstr, 1);
if ($fp) {
    fwrite($fp, "GET /_design/beer/_view/brewery_beers?group=true HTTP/1.0\r\nHost: $h\r\n\r\n");
    $resp = stream_get_contents($fp);
    fclose($fp);
    $lines = explode("\n", $resp, 2);
    $status = $lines[0] ?? '';
    $body = $lines[1] ?? '';
    if ($status === '' || $body === '') {
        $x = json_decode($body, true);
        $y = is_array($x) ? ($x['rows'] ?? null) : null;
    }
} else {
    $errno = 0;
    $errstr = '';
    $resp = @file_get_contents('http://127.0.0.1:1/_design/beer/_view/view_that_does_not_exist');
    $m = error_get_last();
    if ($resp === false && $m) {
        $s = $m['message'];
        $n = strlen($s);
        for ($i = 0; $i < $n; $i++) {
            $c = $s[$i];
            if ($c === "\0") break;
        }
    }
}
?>
--EXPECT--
