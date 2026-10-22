--TEST--
Translated seed (inferredbugs-csharp: gmaps-api-net/3)
--FILE--
<?php
$baseUri = 'https://maps.google.com/maps/api';
$requestUri = '/staticmap?center=0,0&zoom=1&size=1x1';
$uri = rtrim($baseUri, '/') . '/' . ltrim($requestUri, '/');
$parts = parse_url($uri);
$path = ($parts['path'] ?? '') . (isset($parts['query']) ? '?' . $parts['query'] : '');
$stream = fopen('php://temp', 'r+');
$data = '';
for ($i = 0; $i < 3; $i++) {
    $chunk = substr($path, $i, 2);
    fwrite($stream, $chunk);
    $data .= $chunk;
}
rewind($stream);
$read = '';
while (!feof($stream)) {
    $read .= fread($stream, 4096);
}
fclose($stream);
echo $read;
?>
--EXPECT--
