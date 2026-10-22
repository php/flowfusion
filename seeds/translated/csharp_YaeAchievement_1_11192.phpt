--TEST--
Translated seed (inferredbugs-csharp: YaeAchievement/1)
--FILE--
<?php
$cacheDir = __DIR__ . '/cache';
@mkdir($cacheDir, 0777, true);
$path = 'schicksal/version';
$cacheName = $cacheDir . '/' . substr(md5($path), 0, 16) . '.miko';
$data = "bug-pattern:" . $path . "\n";
$etag = '"' . md5($data) . '"';
if (is_file($cacheName)) {
    $gz = gzopen($cacheName, 'rb');
    if ($gz !== false) {
        $cached = '';
        while (!gzeof($gz)) $cached .= gzread($gz, 8192);
        gzclose($gz);
        $parts = explode("\n", $cached, 2);
        if (isset($parts[0]) && str_starts_with($parts[0], "etag:")) {
            $cachedEtag = substr($parts[0], 5);
            if ($cachedEtag === $etag) {
                echo substr($cached, strpos($cached, "\n") + 1);
                exit(0);
            }
        }
    }
}
$body = $data;
$gz = gzopen($cacheName, 'wb9');
if ($gz !== false) {
    gzwrite($gz, "etag:$etag\n$body");
    gzclose($gz);
}
echo $body;
--EXPECT--
