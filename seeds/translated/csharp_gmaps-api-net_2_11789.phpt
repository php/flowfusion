--TEST--
Translated seed (inferredbugs-csharp: gmaps-api-net/2)
--FILE--
<?php
$base = 'https://maps.googleapis.com/maps/api/streetview';
$path = '/?size=1x1&location=0,0';
$uri = rtrim($base, '/') . '/' . ltrim($path, '/');
$ch = curl_init($uri);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$data = curl_exec($ch);
curl_close($ch);
if ($data !== false) {
    $out = '';
    $len = strlen($data);
    for ($i = 0; $i < $len; $i += 4096) {
        $out .= substr($data, $i, 4096);
    }
    echo $out;
}
?>
--EXPECT--
