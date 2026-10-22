--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/85)
--FILE--
<?php
$env = getenv();

$serverUrls = 'http://myServer.com:1234';
$u = parse_url($serverUrls);
if ($u === false || !isset($u['scheme'], $u['host'])) $u = ['scheme' => 'http', 'host' => 'localhost'];
$abs = $u['scheme'] . '://' . $u['host'] . (isset($u['port']) ? ':' . $u['port'] : '') . '/';
echo $abs, PHP_EOL;

$labels = ['' => '', 'k' => 'v'];
foreach ($labels as $k => $v) {
    if ($k === '' && $v === '') continue;
}

putenv('ELASTIC_APM_METRICS_INTERVAL=500ms');
$v = getenv('ELASTIC_APM_METRICS_INTERVAL');
$m = 0;
if (preg_match('/^(-?\d+(?:\.\d+)?)(ms|s|m)?$/', $v, $a)) {
    $n = (float)$a[1];
    $u = $a[2] ?? 's';
    if ($n >= 0) $m = (int)($u === 'ms' ? $n : ($u === 'm' ? $n * 60000 : $n * 1000));
}
echo $m, PHP_EOL;

putenv('ELASTIC_APM_SERVICE_NAME=My.Service.Test');
$s = getenv('ELASTIC_APM_SERVICE_NAME');
$s = preg_replace('/[^a-zA-Z0-9 _-]/', '_', str_replace('.', '_', $s));
echo $s, PHP_EOL;

$patterns = ['*index', '*myPageToIgnore*'];
$tests = ['index', 'INDEX', 'myJsScripts.js', 'bootstrap.css'];
foreach ($tests as $t) {
    $hit = false;
    foreach ($patterns as $p) {
        $re = '/^' . str_replace(['\*', '\?'], ['.*', '.'], preg_quote($p, '/')) . '$/i';
        if (preg_match($re, $t)) {
            $hit = true;
            break;
        }
    }
    echo ($hit ? '1' : '0'), PHP_EOL;
}
?>
--EXPECT--
