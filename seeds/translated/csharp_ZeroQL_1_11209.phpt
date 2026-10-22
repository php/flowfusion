--TEST--
Translated seed (inferredbugs-csharp: ZeroQL/1)
--FILE--
<?php
$base = 'http://localhost:10000/';
$parts = parse_url($base);
$scheme = $parts['scheme'] ?? 'http';
$host = $parts['host'] ?? 'localhost';
$port = isset($parts['port']) ? ':' . $parts['port'] : '';
$path = $parts['path'] ?? '/';
$fp = @fsockopen($host, $parts['port'] ?? 80, $errno, $errstr, 1);
if ($fp) {
    $req = "GET " . ($path === '' ? '/' : $path) . " HTTP/1.0\r\nHost: $host\r\nConnection: close\r\n\r\n";
    fwrite($fp, $req);
    while (!feof($fp)) {
        fread($fp, 8192);
    }
    fclose($fp);
}
$user = ['FirstName' => 'A', 'LastName' => 'B', 'Role' => 'C'];
$json = json_encode($user, JSON_UNESCAPED_SLASHES);
echo $json === false ? '' : $json;
?>
--EXPECT--
